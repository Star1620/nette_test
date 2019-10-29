<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/Vodici/ridic.latte

use Latte\Runtime as LR;

class Template2fdeedbce7 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'title' => 'blockTitle',
	];

	public $blockTypes = [
		'content' => 'html',
		'title' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (!$this->getReferringTemplate() || $this->getReferenceType() === "extends") {
			if (isset($this->params['kus'])) trigger_error('Variable $kus overwritten in foreach on line 13');
			if (isset($this->params['kusvozu'])) trigger_error('Variable $kusvozu overwritten in foreach on line 26');
		}
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>


<div id="banner">

<?php
		$this->renderBlock('title', get_defined_vars());
?>

        <p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Vodici:vodici")) ?>">← zpět na Seznam řidičů</a></p>
</div>

<div id="content">
<?php
		$iterations = 0;
		foreach ($ridic as $kus) {
?>
        <ul>
            <li><b><?php echo LR\Filters::escapeHtmlText($kus->jmeno) /* line 15 */ ?> <?php echo LR\Filters::escapeHtmlText($kus->prijmeni) /* line 15 */ ?></b></li><hr>
            <li>Oddělení : <b><?php echo LR\Filters::escapeHtmlText($kus->oddeleni) /* line 16 */ ?></b></li>
            <li>Řidič : <?php echo LR\Filters::escapeHtmlText($kus->jmeno) /* line 17 */ ?> <?php echo LR\Filters::escapeHtmlText($kus->prijmeni) /* line 17 */ ?></li>
            <li>Zařazen ode dne : <?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->rizeni_od, 'Y-m-d')) /* line 18 */ ?></li><hr>
            <li>Nehody : <?php echo LR\Filters::escapeHtmlText($kus->nehody) /* line 19 */ ?></li>
            <li>Průkaz sk. C : <?php echo LR\Filters::escapeHtmlText($kus->prukaz_c) /* line 20 */ ?></li>
            
        </ul>
<?php
			$iterations++;
		}
?>
    <hr><b>Přiřazená vozidla :</b>
    
<?php
		$iterations = 0;
		foreach ($vozidla as $kusvozu) {
?>
        <ul>
            <li><b><?php echo LR\Filters::escapeHtmlText($kusvozu->spz) /* line 28 */ ?> : <?php echo LR\Filters::escapeHtmlText($kusvozu->vyrobce_vozu) /* line 28 */ ?> <?php
			echo LR\Filters::escapeHtmlText($kusvozu->model_vozu) /* line 28 */ ?></b></li>
            <li>Platné : <?php echo LR\Filters::escapeHtmlText($kusvozu->prirazeni_zacatek) /* line 29 */ ?> - <?php
			echo LR\Filters::escapeHtmlText($kusvozu->prirazeni_konec) /* line 29 */ ?></li>
            <li>Zařazen ode dne : <?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->rizeni_od, 'Y-m-d')) /* line 30 */ ?></li>
            <li>Poruchy : <?php echo LR\Filters::escapeHtmlText($kusvozu->poruchy) /* line 31 */ ?></li>

        </ul>
            <hr>
<?php
			$iterations++;
		}
?>
    
    
    
    
    
</div>

<?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?>	<h1>Detail řidiče</h1>
<?php
	}

}
