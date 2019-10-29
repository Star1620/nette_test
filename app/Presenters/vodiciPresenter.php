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
    
    public function renderVodici(): void {
        $this->template->vodici = $this->database->table('ridici')
                ->order('oddeleni');
    }
    
     public function renderRidic(int $id): void {
        $this->template->ridic = $this->database->table('ridici')
                ->where('id', $id)
                ->limit(1);
        
        $this->template->vozidla = $this->database->query('SELECT id_vozu, prirazeni_zacatek, prirazeni_konec, spz, vyrobce_vozu, model_vozu, poruchy FROM prirazeni_vozu_ridici'
             . ' LEFT JOIN vozy ON prirazeni_vozu_ridici.id_vozu = vozy.id'
             . ' WHERE id_ridice = "'.$id.'";');
    }
    
    protected function createComponentNovyridicForm(): Form {
        
        $seznamOddeleni = array(
            'servis' => 'servis',
            'obchod' => 'obchod',
            'prodej' => 'prodej'
            );
        $form = new Form;
    
        $form->addtext('jmeno', 'Jméno:')->setRequired();
        $form->addtext('prijmeni', 'Příjmení:')->setRequired();
        $form->addSelect('oddeleni', 'Oddělení:', $seznamOddeleni);
        $form->addCheckbox('prukaz_c', ' - vlastník ř.průkazu sk. C');
        $form->addSubmit('send', ' ODESLAT ');
        $form->addHidden('rizeni_od', date('Y-m-d'));
        $form->onSuccess[] = [$this, 'novyridicFormSucceeded'];
        return $form;
    }
    
     public function novyridicFormSucceeded(Form $form, array $values): void {
        
        $ridicePridej = $this->database->table('ridici')->insert($values);
        
        if($ridicePridej) {
            $this->flashMessage('Nový řidič byl vložen', 'success');
            $this->redirect('vodici');
        } else {
            $this->flashMessage('Nepodařilo se vložit nového řidiče !', 'false');
            $this->redirect('vodici');
        }
        
               
        
        
    }
    
    
}