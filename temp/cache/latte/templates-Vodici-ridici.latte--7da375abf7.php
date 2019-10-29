<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/Vodici/ridici.latte

use Latte\Runtime as LR;

class Template7da375abf7 extends Latte\Runtime\Template
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

        <p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">← zpět na Vozový park</a></p>
</div>

<div id="content">
<?php
		$iterations = 0;
		foreach ($ridic as $kus) {
?>
       
            <div class="row">
                <div class="col-2"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Ridici:ridici", [$kus->id])) ?>"><?php
			echo LR\Filters::escapeHtmlText($kus->jmeno) /* line 16 */ ?> <?php echo LR\Filters::escapeHtmlText($kus->prijmeni) /* line 16 */ ?></a></div>
                <div class="col-2"><?php echo LR\Filters::escapeHtmlText($kus->oddeleni) /* line 17 */ ?></a></div>
                <div class="col-2"><?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->povoleno_od, 'j.n.Y')) /* line 18 */ ?></a></div>
                <div class="col-3"><?php echo LR\Filters::escapeHtmlText($kus->nehody) /* line 19 */ ?> - <?php
			echo LR\Filters::escapeHtmlText($vuz->model_vozu) /* line 19 */ ?></div>
                <div class="col-3"><?php echo LR\Filters::escapeHtmlText($kus->prukaz_c) /* line 20 */ ?></div>
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
