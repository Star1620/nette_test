<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/Vozy/novy.latte

use Latte\Runtime as LR;

class Template5f2d75c9a7 extends Latte\Runtime\Template
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
		/* line 14 */ $_tmp = $this->global->uiControl->getComponent("vuzForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
		$_tmp->render();
?>

</div>

<?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?>	<h1>Vozový park - přidat nový vůz</h1>
<?php
	}

}
