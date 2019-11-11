<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Utils\DateTime;

class UzivatelePresenter extends Nette\Application\UI\Presenter {
    
    private $database;
    
    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }
     
    protected function createComponentPrihlaseniForm(): Form {
        
        $form = new Form;
    
        $form->addtext('username', 'Zadejte email:')->setRequired();
        $form->addPassword('password', 'Heslo:')->setRequired();
        $form->addSubmit('send', ' ODESLAT ');
        $form->onSuccess[] = [$this, 'prihlaseniFormSucceeded'];
        return $form;
    }
    
    private function seznamRoli() {
        $nacteneRole = $this->database->table('role');
        $seznamRoli = array();
        foreach ($nacteneRole as $key) {
            $seznamRoli += [$key["kod_role"] => $key["nazev_role"]];
        }
        return $seznamRoli;
    }
    
    private function seznamOddeleni() {
        $nacteneOddeleni = $this->database->table('oddeleni');
        $seznamOddeleni = array();
        foreach ($nacteneOddeleni as $key) {
            $seznamOddeleni += [$key["nazev_oddeleni"] => $key["nazev_oddeleni"]];
        }
        return $seznamOddeleni;
    }

    protected function createComponentNovyuzivatelForm(): Form {

        $form = new Form;

        $form->addtext('jmeno', 'Jméno:')->setRequired();
        $form->addtext('prijmeni', 'Příjmení:')->setRequired();
        $form->addEmail('email', 'Email:')->setRequired();
        $form->addSelect('oddeleni', 'Oddělení:', $this->seznamOddeleni());
        $form->addPassword('password', 'Heslo:')->setRequired();
        $form->addSelect('role', 'Role:', $this->seznamRoli())->setRequired();
        $form->addSelect('aktivniridic', 'Uložit jako aktivního řidiče: ', array(1=>'ANO', 0=> 'NE'));
        $form->addSelect('sk_b', 'Vlastník ř. průkazu sk. B: ', array(1=>'ANO', 0=> 'NE'));
        $form->addSelect('sk_c', 'Vlastník ř. průkazu sk. C: ', array(0=> 'NE', 1=>'ANO'));
        $form->addSelect('sk_vzv', 'Vlastník ř. průkazu pro vysokozdvižný vozík: ', array(0=> 'NE', 1=>'ANO'));
        $form->addSubmit('send', ' ODESLAT ');
        $form->onSuccess[] = [$this, 'novyuzivatelFormSucceeded'];
        return $form;
    }
   
    public function renderUzivatele(): void {
        
        $this->template->uzivatele = $this->database->query('SELECT username, role, jmeno, prijmeni, oddeleni, rizeni_od FROM users'
                . ' JOIN ridici ON ridici.email = users.username'
                . ' ORDER BY oddeleni, prijmeni, rizeni_od');
        
        $this->seznamRoli();
    }
    
    public function renderUzivatel(string $username): void {
        
        $this->template->uzivatel = $this->database->query('SELECT username, role, jmeno, prijmeni, aktivni_uzivatel, oddeleni, rizeni_od, rizeni_do, sk_b, sk_c, sk_vzv, nehody FROM users'
                . ' JOIN ridici ON ridici.email = users.username'
                . ' WHERE username = "'.$username.'";');
        
    }

    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->redirect('Homepage:default');
    }

    private function ulozSession(string $username, int $role) {
        
        $idSess = session_id();
        $ipSess = $_SERVER["REMOTE_ADDR"];
     //   $datum = new \DateTime($time, $object)
        $sessDo = DateTime::from(1200);
        $sessData = array('sess_id' => $idSess, 'sess_user' => $username, 'sess_priv' => $role, 'sess_ip' => $ipSess, 'sess_do' => $sessDo);
        $this->database->table('session')->insert($sessData);
    }
    
    public function prihlaseniFormSucceeded(Form $form, \stdClass $values): void {
        
        try {
            $this->getUser()->login($values->username, $values->password);
            $this->ulozSession($values->username, $this->user->roles['role']);
            $this->redirect('Homepage:default');
        } catch (Nette\Security\AuthencationException $e) {
            $form->addError('Nesprávné jméno nebo heslo.');
        }
    }
    
    private function ulozridice(array $ulozridice) {
        try {
            $this->database->table('ridici')->insert($ulozridice);
        } catch(Nette\Database\DriverException $e) {
            $this->flashMessage('Chyba - uživatel nebyl vložen do databáze.', 'false');
            $this->redirect('Vodici:vodici');
            }
 //       return $emailridice;
    }
    
    private function ulozuzivatele(array $ulozuzivatele) {
        try {
            $row = $this->database->table('users')->insert($ulozuzivatele);
            $ulozenyuser = $row->id;
        } catch(Nette\Database\DriverException $e) {
            $this->flashMessage('Chyba - uživatel nebyl vložen do databáze.', 'false');
            $this->redirect('Uzivatele:uzivatel');
            }
        return $ulozenyuser;
    }
    
    public function novyuzivatelFormSucceeded(Form $form, \stdClass $values): void {
        

            $ridicexistuje = $this->database->query('SELECT email, jmeno, prijmeni FROM ridici WHERE email = "'.$values->email.'"')->fetch();
        
        if(!$ridicexistuje) {
            ($values->aktivniridic > 0) ? $values->aktivniridic = '9999-12-31' : $values->aktivniridic = date('Y-m-d');
            $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
            $values->password = $passwords->hash($values->password);
            $ulozridice = array('jmeno' => $values->jmeno, 'prijmeni' => $values->prijmeni, 'email' => $values->email, 'oddeleni' => $values->oddeleni, 'rizeni_od' => date('Y-m-d'), 'sk_b' => $values->sk_b, 'sk_c' => $values->sk_c, 'sk_vzv' => $values->sk_vzv, 'rizeni_do' => $values->aktivniridic);
           
            try {
                $this->ulozridice($ulozridice);
                $ulozuzivatele = array('username' => $values->email, 'password' => $values->password, 'role' => $values->role, 'aktivni_uzivatel' => 1);
                $this->ulozuzivatele($ulozuzivatele);
                $this->flashMessage('Nový uživatel '.$values->jmeno.' '.$values->prijmeni.' byl vložen do databáze.', 'success');
                $this->redirect('Uzivatele:uzivatel', $values->email);
                
            } catch(Nette\Database\DriverException $e) {
                    $form->addError('Nepodařilo se uložit řidiče do databáze.');
            }

        } else {
            $this->flashMessage('Chyba - uživatel nebyl vložen do databáze.', 'false');
            $form->addError('Neuloženo - email '.$values->email.' již existuje pro uživatele : '.$ridicexistuje->jmeno.' '.$ridicexistuje->prijmeni);
        }
    
    }
        
    
}