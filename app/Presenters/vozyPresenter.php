<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
//use App\Models\ViditelnostOddeleni;

class VozyPresenter extends Nette\Application\UI\Presenter {
    
    private $database;
    private $idservisu = 'servis@test.com';  // nové vozy jsou automaticky přiřazeny servisu, než budou prohlédnuty a přiděleny řidiči
//    private $viditelnost;
    
    
    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }
            
//    private function zobrazOddeleni() {
//
//        $this->viditelnost = $this->ViditelnostOddeleni()->zobrazOddeleni();
//        
//    }
    
    public function renderVozy(): void {
        $this->template->vozy = $this->database->query('SELECT vin, spz, provoz_od, prirazeni_zacatek, prirazeni_konec, jmeno, prijmeni, oddeleni, vyrobce, model FROM vozy'
             . ' INNER JOIN prirazeni_vozu_ridici ON vozy.vin = prirazeni_vozu_ridici.id_vozu'
             . ' INNER JOIN ridici ON ridici.email = prirazeni_vozu_ridici.email_ridice'
             . ' INNER JOIN modely_vozu ON modely_vozu.id_modelu_vozu = vozy.model_vozu'
             . ' WHERE prirazeni_konec > "'.date("Y-m-d H-i-s").'" '   
             . ' ORDER BY oddeleni, prijmeni, vin');
   
    }
    
    
    public function renderVuz(string $vin) {
  
     $this->template->vuz = $this->database->query('SELECT vin, spz, provoz_od, provoz_do, posledni_servis, posledni_stk, vuz_poznamka, pneu, rid_prukaz, jmeno, prijmeni, oddeleni, prirazeni_zacatek, prirazeni_konec, vyrobce, model, palivo, servisni_prohlidky_mesice FROM prirazeni_vozu_ridici'
             . ' INNER JOIN vozy ON prirazeni_vozu_ridici.id_vozu = vozy.vin'
             . ' INNER JOIN ridici ON prirazeni_vozu_ridici.email_ridice = ridici.email'
             . ' LEFT JOIN modely_vozu ON modely_vozu.id_modelu_vozu = vozy.model_vozu'
             . ' WHERE vin = "'.$vin.'" AND prirazeni_konec > "'.date("Y-m-d H-i-s").'";');
    }
    
    protected function createComponentVuzForm(): Form {
        
        $vyberModelvozu = $this->vytvorVyberModelvozu();
        $form = new Form;
    
        $form->addtext('vin', 'VIN:')->setRequired();
        $form->addtext('spz', 'SPZ:')->setRequired();
        $form->addSelect('model_vozu', 'Model vozu:', $vyberModelvozu);
    //    $form->addSelect('oddeleni', 'Oddělení:', array('servis', 'obchod', 'prodej'));
    //    $form->addtext('ridic', 'Řidič:');
        $form->addText('posledni_servis', 'Poslední datum servisu :');
        $form->addText('posledni_stk', 'Posledni datum STK :');
        $form->addSelect('pneu', 'Pneu na vozidle:', array('letni' => 'letní', 'zimni' => 'zimní', 'celorocni' => 'celoroční'));
        $form->addText('vuz_poznamka', 'Poznámka:');
        $form->addSubmit('send', ' ODESLAT ');
        $form->onSuccess[] = [$this, 'vuzFormSucceeded'];
        return $form;
    }

    private function vytvorVyberModelvozu() {
        
        $vyberModelvozu = array();
        $nacteniModeluvozu = $this->database->table('modely_vozu')
                ->order('vyrobce');
        if(!$nacteniModeluvozu) {
            $this->flashMessage('Nenačtena DATA !', 'false');
            $this->redirect('Homepage:default');
        } else {
            foreach ($nacteniModeluvozu as $value) {
                $klic = $value->id_modelu_vozu;
                $hodnota = $value->vyrobce." ".$value->model." - ".$value->palivo;
                $vyberModelvozu += [ $klic => $hodnota ];
            }
        }
        return $vyberModelvozu;
    }
    
    private function vytvorVyberRidici() {
        
        $vyberRidici = array();
        $nacteniRidicu = $this->database->table('ridici')
                ->order('oddeleni');
        if(!$nacteniRidicu) {
            $this->flashMessage('Nenačtena DATA !', 'false');
            $this->redirect('Vodici:vodici');
        } else {
            foreach ($nacteniRidicu as $value) {
                $klic = $value->email;
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
//        $form->addHidden('vuz', $this->vin);
        $form->addSubmit('send', ' ODESLAT ');
        $form->onSuccess[] = [$this, 'vuzupdateFormSucceeded'];
        return $form;
    }
    
    protected function createComponentVuzprirazeniForm(): Form {
               
        $vyberRidici = $this->vytvorVyberRidici();
        $form = new Form;

//        $form->addSelect('oddeleni', 'Oddělení:', array('servis' => 'servis', 'obchod' => 'obchod', 'prodej' => 'prodej'));
        $form->addSelect('ridic', 'Řidič:', $vyberRidici);
        
        $form->addSubmit('send', ' ODESLAT ');
        $form->onSuccess[] = [$this, 'vuzprirazeniFormSucceeded'];
        return $form;
    }

    
    public function renderUpravit(string $id) {
        
        $this->template->vuz = $this->database->query('SELECT vin, spz, vyrobce, model, provoz_od, posledni_servis, posledni_stk, jmeno, prijmeni, oddeleni, prirazeni_zacatek FROM prirazeni_vozu_ridici'
             . ' INNER JOIN vozy ON prirazeni_vozu_ridici.id_vozu = vozy.vin'
             . ' INNER JOIN ridici ON prirazeni_vozu_ridici.email_ridice = ridici.email'
             . ' INNER JOIN modely_vozu ON modely_vozu.id_modelu_vozu = vozy.model_vozu'
             . ' WHERE prirazeni_konec > "'.date("Y-m-d H-i-s").'" AND vin = "'.$id.'" LIMIT 1;');
        
    }
    
    public function actionUpravit(string $id): void {
        $upravit = $this->database->table('vozy')->get($id);
        if(!$upravit) {
            $this->error('Vůz nenalezen.');
        }
        $this['vuzeditForm']->setDefaults($upravit->toArray());
    }
    
    private function vlozPrirazeni(string $idVozu, string $idRidice): void {
        
        $vlozPrirazeni = $this->database->query('INSERT INTO prirazeni_vozu_ridici (id_vozu, email_ridice) VALUES ("'.$idVozu.'", "'.$idRidice.'")');
        if(!$vlozPrirazeni) {
            $this->flashMessage('Záznam vozidla do přiřazovací tabulky nebyl vložen !', 'false');
        } else {
            $this->flashMessage('Záznam vozidla do přiřazovací tabulky byl vložen.', 'success');
        }
        
    }

    public function vuzFormSucceeded(Form $form, array $values): void {
        
        
//        $vuzPridej = $this->database->table('vozy')->insert($values);
        
        try {
            $this->database->table('vozy')->insert($values);
            $this->vlozPrirazeni($values['vin'], $this->idservisu);  // příjem nového vozu servisem, než bude přiřazeno
            $this->flashMessage('Záznam vozidla byl vložen', 'success');
            $this->redirect('vuz', $values['vin']);
           
        } catch(Nette\Database\DriverException $e) {
            $form->addError('Nepodařilo se uložit vůz do databáze - VIN: '.$values["vin"].' již existuje.');
//            $this->flashMessage('Záznam vozidla do přiřazovací tabulky nebyl vložen !', 'false');
//            $this->redirect('Homepage:default');
        }

    }
    
    public function vuzupdateFormSucceeded(Form $form, array $values): void {
        $id = $this->getParameter('id');
        $this->database->table('vozy')->get($id)->update($values);
     
            $this->flashMessage('Záznam vozidla byl vložen', 'success');
            $this->redirect('Vozy:vuz', $id);
    }
    
    public function vuzprirazeniFormSucceeded(Form $form, array $values): void {
        $id = $this->getParameter('id');
 //       $vuzUpdate = $this->database->table('prirazeni_vozu_ridici')->get($id)->update($values);
//        $vuzUpdate->update($values);
        // vyhledaní zda vin má prirazeni_konec vyšší než předané datum
        
//        if(!$hledani['email_ridice'] = $this->database->query('SELECT email_ridice FROM prirazeni_vozu_ridici '
//                . ' WHERE id_vozu = "'.$id.'" '
//                . ' AND prirazeni_konec > "'.date("Y-m-d H-i-s").'"')) {
       if(!$hledani = $this->database->table('prirazeni_vozu_ridici')->where('id_vozu = ?', $id)->where('prirazeni_konec > ?', date('Y-m-d H-i-s'))->update(array('prirazeni_konec' => date('Y-m-d H-i-s'))) ) {
        
        
            echo "<br> přiřazení nenalezeno.";
            die();
        } else {
            $this->vlozPrirazeni($id, $values['ridic']);
            $form->addError('Vozidlo bylo přiřazeno.');
        }
        
                
//            $this->flashMessage('Záznam vozidla byl vložen', 'success');
//            $this->redirect('Vozy:vuz', $vuzUpdate['vin']);
    }
    
}