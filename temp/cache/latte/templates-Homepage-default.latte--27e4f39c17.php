<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template27e4f39c17 extends Latte\Runtime\Template
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
			if (isset($this->params['vuz'])) trigger_error('Variable $vuz overwritten in foreach on line 30');
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
        
</div>
<div id="content">
            <div class="row">
                <div class="col-3"><b>ZOBRAZIT / SKRÝT :</b></div>
                <div class="col-9">
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary btn-sm zobraz" id="spz">SPZ</button>
                        <button class="btn btn-primary btn-sm zobraz" id="oddeleni">Oddělení</button>
                        <button class="btn btn-primary btn-sm zobraz" id="provozOd">Provoz od</button>
                        <button class="btn btn-primary btn-sm zobraz" id="znacka">Značka vozu</button>
                        <button class="btn btn-primary btn-sm zobraz" id="ridic">Přiřazeno řidiči</button>
                        <button class="btn btn-primary btn-sm zobraz" id="poruchy">Poruchy</button>
                    </div>
                </div><hr>
            </div>
            <div class="row">
                <div class="col-2 prvniSloupec"><b>SPZ</b></div>
                <div class="col-1 druhySloupec"><b>Oddělení</b></div>
                <div class="col-2 tretiSloupec"><b>Provoz od</b></div>
                <div class="col-3 ctvrtySloupec"><b>Značka vozu</b></div>
                <div class="col-3 patySloupec"><b>Přiřazeno řidiči</b></div>
                <div class="col-1 sestySloupec"><b>Poruchy</b></div>
            </div>
<?php
		$iterations = 0;
		foreach ($vozy as $vuz) {
?>
            <div class="row">
                <div class="col-2 prvniSloupec"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Vozy:vuz", [$vuz->vin])) ?>"><?php
			echo LR\Filters::escapeHtmlText($vuz->spz) /* line 32 */ ?></a></div>
                <div class="col-1 druhySloupec"><?php echo LR\Filters::escapeHtmlText($vuz->oddeleni) /* line 33 */ ?></div>
                <div class="col-2 tretiSloupec"><?php echo LR\Filters::escapeHtmlText(($this->filters->date)($vuz->prirazeni_zacatek, 'Y-m-d')) /* line 34 */ ?></div>
                <div class="col-3 ctvrtySloupec"><?php echo LR\Filters::escapeHtmlText($vuz->vyrobce_vozu) /* line 35 */ ?> - <?php
			echo LR\Filters::escapeHtmlText($vuz->model_vozu) /* line 35 */ ?></div>
                <div class="col-3 patySloupec"><?php echo LR\Filters::escapeHtmlText($vuz->jmeno) /* line 36 */ ?> <?php
			echo LR\Filters::escapeHtmlText($vuz->prijmeni) /* line 36 */ ?></div>
                <div class="col-1 sestySloupec"><?php echo LR\Filters::escapeHtmlText($vuz->poruchy) /* line 37 */ ?></div>
            </div>
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
?>	<h1>Vozový PARK</h1>
<?php
	}

}
