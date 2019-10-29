<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;



final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $database;
    
    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }
    
    public function renderDefault(): void {
 //       $this->template->vozy = $this->database->table('vozy')
 //               ->order('id')
 //               ->limit(3);
 
        $this->template->vozy = $this->database->query('SELECT vin, spz, vyrobce_vozu, model_vozu, provoz_od, poruchy, prirazeni_zacatek, prirazeni_konec, jmeno, prijmeni, oddeleni FROM vozy'
             . ' LEFT JOIN prirazeni_vozu_ridici ON vozy.id = prirazeni_vozu_ridici.id_vozu'
             . ' LEFT JOIN ridici ON ridici.id = prirazeni_vozu_ridici.id_ridice'
             . ' ORDER BY oddeleni, prijmeni, id_vozu');             
    }
  
    public function renderVuz(string $vin) {
  
            $this->template->vozy = $this->database->query('SELECT vin, spz, vyrobce_vozu, FROM prirazeni_vozu_ridici'
             . ' INNER JOIN vozy ON prirazeni_vozu_ridici.id_vozu = vozy.id'
             . ' INNER JOIN ridici ON prirazeni_vozu_ridici.id_ridice = ridici.id'
             . ' WHERE vin = "'.$vin.'";');
    }
    
    
    
}


