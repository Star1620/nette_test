<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/Vodici/vodici.latte

use Latte\Runtime as LR;

class Template01a7783fbd extends Latte\Runtime\Template
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
			if (isset($this->params['kus'])) trigger_error('Variable $kus overwritten in foreach on line 19');
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

        <p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">← zpět na Vozový park</a></p>
</div>

<div id="content">
            <div class="row">
                <div class="col-3"><b>Řidič / -ka:</b></div>
                <div class="col-2"><b>Oddělení:</b></div>
                <div class="col-2"><b>Povoleno od dne:</b></div>
                <div class="col-2"><b>Nehody:</b></div>
                <div class="col-2"><b>Průkaz sk.C:</b></div>
            </div>
    
<?php
		$iterations = 0;
		foreach ($vodici as $kus) {
?>
       
            <div class="row">
                <div class="col-3"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Vodici:ridic", [$kus->id])) ?>"><?php
			echo LR\Filters::escapeHtmlText($kus->jmeno) /* line 22 */ ?> <?php echo LR\Filters::escapeHtmlText($kus->prijmeni) /* line 22 */ ?></a></div>
                <div class="col-2"><?php echo LR\Filters::escapeHtmlText($kus->oddeleni) /* line 23 */ ?></a></div>
                <div class="col-2"><?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->rizeni_od, 'Y-m-d')) /* line 24 */ ?></a></div>
                <div class="col-2"><?php echo LR\Filters::escapeHtmlText($kus->nehody) /* line 25 */ ?></div>
                <div class="col-2"><?php echo LR\Filters::escapeHtmlText($kus->prukaz_c) /* line 26 */ ?></div>
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
?>	<h1>Přehled řidičů</h1>
<?php
	}

}
