<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/Vozy/vozy.latte

use Latte\Runtime as LR;

class Templateb6758f4ff1 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'content' => 'html',
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
			if (isset($this->params['kus'])) trigger_error('Variable $kus overwritten in foreach on line 8');
		}
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<div class="container">
        <div class="row">
            <h1>Stránka - Vozy 2</h1>
            <p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">← zpět na výpis vozů</a></p>
        </div>
<?php
		$iterations = 0;
		foreach ($vozy as $kus) {
?>
        <div class="row">
            <div class="col-3">
                <h1>Vůz č.<?php echo LR\Filters::escapeHtmlText($kus->id) /* line 11 */ ?></h1>
            </div>
            <div class="col-3">
                <h2><?php echo LR\Filters::escapeHtmlText($kus->vyrobce_vozu) /* line 14 */ ?> - <?php echo LR\Filters::escapeHtmlText($kus->model_vozu) /* line 14 */ ?></h2>
            </div>
            <div class="col-3">
                <h2>SPZ : <?php echo LR\Filters::escapeHtmlText($kus->spz) /* line 17 */ ?></h2>
            </div>
            <div class="col-3">

            </div>
        </div>
<?php
			$iterations++;
		}
?>


    
</div>
<?php
	}

}
