<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class VodiciPresenter extends Nette\Application\UI\Presenter {
    
    private $database;
    
    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }
    
    public function actionCreate(): void {
        
        if(!$this->getUser()->isLoggedIn()) {
            $this->flashMessage('Přístup pouze pro přihlášené uživatele.', 'false');
            $this->redirect('Homepage:default');
        }
        
    }
    
    public function renderVodici(): void {
        $this->actionCreate();
        $this->template->vodici = $this->database->table('ridici')
                ->order('oddeleni');
    }
    
     public function renderRidic(string $id): void {
        if($this->user->roles['role'] < 20){
      
        $this->template->ridic = $this->database->table('ridici')
                ->where('email', $id)
                ->limit(1);
        
        $this->template->vozidla = $this->database->query('SELECT vin, prirazeni_zacatek, prirazeni_konec, spz, vyrobce, model FROM prirazeni_vozu_ridici'
             . ' LEFT JOIN vozy ON prirazeni_vozu_ridici.id_vozu = vozy.vin'
             . ' LEFT JOIN modely_vozu ON modely_vozu.id_modelu_vozu = vozy.model_vozu'
             . ' WHERE email_ridice = "'.$id.'";');
        } else {
            $this->flashMessage('Přístup pouze pro přihlášeného vedoucího oddělení.', 'false');
            $this->redirect('Homepage:default'); 
        }
        
    }
    
    private function seznamOddeleni() {
        $nacteneOddeleni = $this->database->table('oddeleni');
        $seznamOddeleni = array();
        foreach ($nacteneOddeleni as $key) {
            $seznamOddeleni += [$key["nazev_oddeleni"] => $key["nazev_oddeleni"]];
        }
        return $seznamOddeleni;
    }
        
    protected function createComponentNovyridicForm(): Form {
        
        $form = new Form;
    
        $form->addtext('jmeno', 'Jméno:')->setRequired();
        $form->addtext('prijmeni', 'Příjmení:')->setRequired();
        $form->addEmail('email', 'Email:')->setRequired();
        $form->addSelect('oddeleni', 'Oddělení:', $this->seznamOddeleni());
        $form->addCheckbox('sk_b', ' - vlastník ř.průkazu sk. B');
        $form->addCheckbox('sk_c', ' - vlastník ř.průkazu sk. C');
        $form->addCheckbox('sk_vzv', ' - vlastník ř.průkazu pro vysokozdvižný vozík');
        $form->addCheckbox('skoleni_do', ' - absolvoval školení pro řidiče');
        $form->addSubmit('send', ' ODESLAT ');
        $form->addHidden('rizeni_od', date('Y-m-d'));
        $form->onSuccess[] = [$this, 'novyridicFormSucceeded'];
        return $form;
    }
    
     public function novyridicFormSucceeded(Form $form, array $values): void {
        
        $ridicePridej = $this->database->table('ridici')->insert($values);
        
        if($ridicePridej) {
            $this->flashMessage('Nový řidič byl vložen', 'success');
            $this->redirect('Vodici:vodici');
        } else {
            $this->flashMessage('Nepodařilo se vložit nového řidiče !', 'false');
            $this->redirect('Vodici:vodici');
        }
  
        
    }
    
    
}