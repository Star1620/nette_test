<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class VozyPresenter extends Nette\Application\UI\Presenter {
    
    private $database;

    
    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }
    
  //  public function renderVuz(string $vin) {
  //      $this->template->vuz = $this->database->table('vozy')->get($vin);
  //  }
    
    public function renderVozy(): void {
        $this->template->vozy = $this->database->table('vozy')
                ->order('id')
                ->limit(3);
    }
    
    
    public function renderVuz(string $vin) {
  
     $this->template->vuz = $this->database->query('SELECT vin, spz, vyrobce_vozu, model_vozu, provoz_od, posledni_servis, posledni_stk, poruchy, jmeno, prijmeni, oddeleni, prirazeni_zacatek, id_vozu FROM prirazeni_vozu_ridici'
             . ' INNER JOIN vozy ON prirazeni_vozu_ridici.id_vozu = vozy.id'
             . ' INNER JOIN ridici ON prirazeni_vozu_ridici.id_ridice = ridici.id'
             . ' WHERE vin = "'.$vin.'";');
    }
    
    protected function createComponentVuzForm(): Form {
        
        $form = new Form;
    
        $form->addtext('vin', 'VIN:')->setRequired();
        $form->addtext('spz', 'SPZ:')->setRequired();
        $form->addtext('vyrobce_vozu', 'Značka vozu:')->setRequired();
        $form->addtext('model_vozu', 'Model vozu:');
    //    $form->addSelect('oddeleni', 'Oddělení:', array('servis', 'obchod', 'prodej'));
    //    $form->addtext('ridic', 'Řidič:');
        $form->addText('posledni_servis', 'Poslední datum servisu :');
        $form->addText('posledni_stk', 'Posledni datum STK :')->setRequired();
        $form->addSubmit('send', ' ODESLAT ');
        $form->onSuccess[] = [$this, 'vuzFormSucceeded'];
        return $form;
    }

    private function vytvorVyberRidici() {
        
        $vyberRidici = array();
        $nacteniRidicu = $this->database->table('ridici')
                ->order('oddeleni');
        if(!$nacteniRidicu) {
            $this->flashMessage('Nenačtena DATA !', 'false');
            $this->redirect('Homepage');
        } else {
            foreach ($nacteniRidicu as $value) {
                $klic = $value->id;
                $hodnota = $value->oddeleni." - ".$value->prijmeni." ".$value->jmeno;
                $vyberRidici += [ $klic => $hodnota ];
            }
        }
        return $vyberRidici;
    }
    
    protected function createComponentVuzeditForm(): Form {
               
        $vyberRidici = $this->vytvorVyberRidici();
        $form = new Form;

 //       $form->addSelect('oddeleni', 'Oddělení:', array('servis' => 'servis', 'obchod' => 'obchod', 'prodej' => 'prodej'));
 //       $form->addSelect('ridic', 'Řidič:', $vyberRidici);
        $form->addText('posledni_servis', 'Poslední datum servisu :');
        $form->addText('posledni_stk', 'Posledni datum STK :');

        $form->addSubmit('send', ' ODESLAT ');
        $form->onSuccess[] = [$this, 'vuzupdateFormSucceeded'];
        return $form;
    }

    
    public function renderUpravit(int $id) {
        
        $this->template->vuz = $this->database->query('SELECT vin, spz, vyrobce_vozu, model_vozu, provoz_od, posledni_servis, posledni_stk, poruchy, jmeno, prijmeni, oddeleni, id_vozu, prirazeni_zacatek FROM prirazeni_vozu_ridici'
             . ' INNER JOIN vozy ON prirazeni_vozu_ridici.id_vozu = vozy.id'
             . ' INNER JOIN ridici ON prirazeni_vozu_ridici.id_ridice = ridici.id'
             . ' WHERE id_vozu = "'.$id.'" LIMIT 1;');
        
    }
    
    public function actionUpravit(int $id): void {
        $upravit = $this->database->table('vozy')->get($id);
        if(!$upravit) {
            $this->error('Vůz nenalezen.');
        }
        $this['vuzeditForm']->setDefaults($upravit->toArray());
    }
    
    private function vlozPrirazeni(int $idVozu, int $idRidice): void {
        
        $vlozPrirazeni = $this->database->query('INSERT INTO prirazeni_vozu_ridici (id_vozu, id_ridice) VALUES ("'.$idVozu.'", "'.$idRidice.'")');
        if(!$vlozPrirazeni) {
            $this->flashMessage('Záznam vozidla do přiřazovací tabulky nebyl vložen !', 'false');
        } else {
            $this->flashMessage('Záznam vozidla do přiřazovací tabulky byl vložen.', 'success');
        }
        
    }

    public function vuzFormSucceeded(Form $form, array $values): void {
        
        $vuzPridej = $this->database->table('vozy')->insert($values);
        
        if($vuzPridej) {
            $this->vlozPrirazeni($vuzPridej->id, 8);  // příjem nového vozu servisem, než bude přiřazeno
            $this->flashMessage('Záznam vozidla byl vložen', 'success');
            $this->redirect('vuz', $vuzPridej->vin);
           
        } else {
            $this->flashMessage('Záznam vozidla do přiřazovací tabulky nebyl vložen !', 'false');
            $this->redirect('Homepage:default');
        }

    }
    
    public function vuzupdateFormSucceeded(Form $form, array $values): void {
        $id = $this->getParameter('id');
        $vuzUpdate = $this->database->table('vozy')->get($id);
        $vuzUpdate->update($values);
        
            $this->flashMessage('Záznam vozidla byl vložen', 'success');
            $this->redirect('Homepage:default');
    }
    
}