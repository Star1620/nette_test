<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/Vuz/vuz.latte

use Latte\Runtime as LR;

class Templateaf5d0f66dd extends Latte\Runtime\Template
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

        <p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">← zpět na výpis vozů</a></p>
</div>

<div id="content">
<?php
		$iterations = 0;
		foreach ($vuz as $kus) {
?>
        <ul>
            <li>Vůz : <b><?php echo LR\Filters::escapeHtmlText($kus->spz) /* line 15 */ ?> : <?php echo LR\Filters::escapeHtmlText($kus->vyrobce_vozu) /* line 15 */ ?> - <?php
			echo LR\Filters::escapeHtmlText($kus->model_vozu) /* line 15 */ ?></b></li><hr>
            <li>Oddělení : <b><?php echo LR\Filters::escapeHtmlText($kus->oddeleni) /* line 16 */ ?></b></li>
            <li>Řidič : <?php echo LR\Filters::escapeHtmlText($kus->jmeno) /* line 17 */ ?> <?php echo LR\Filters::escapeHtmlText($kus->prijmeni) /* line 17 */ ?></li>
            <li>Zapůjčeno dne : <?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->ridic_od, 'j. n. Y')) /* line 18 */ ?></li><hr>
            <li>Ve vozovém parku od : <?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->provoz_od, 'j. n. Y')) /* line 19 */ ?></li>
            <li>VIN : <?php echo LR\Filters::escapeHtmlText($kus->vin) /* line 20 */ ?></li>
            <li>Poslední servis : <?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->posledni_servis, 'j. n. Y')) /* line 21 */ ?></li>
            <li>Posledni STK : <?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->posledni_stk, 'j. n. Y')) /* line 22 */ ?></li>
            <li>Havárie a poruchy : <?php echo LR\Filters::escapeHtmlText($kus->poruchy) /* line 23 */ ?></li>
        </ul>
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
?>	<h1>Vozový park - vůz </h1>
<?php
	}

}
