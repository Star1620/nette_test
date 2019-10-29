<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Templatea935db4ff6 extends Latte\Runtime\Template
{
	public $blocks = [
		'scripts' => 'blockScripts',
	];

	public $blockTypes = [
		'scripts' => 'html',
	];


	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <link rel="stylesheet" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 8 */ ?>/css/vlozenystyl.css">
        <title><?php
		if (isset($this->blockQueue["title"])) {
			$this->renderBlock('title', $this->params, function ($s, $type) {
				$_fi = new LR\FilterInfo($type);
				return LR\Filters::convertTo($_fi, 'html', $this->filters->filterContent('striphtml', $_fi, $s));
			});
			?> | <?php
		}
?>Nette Web</title>
</head>

<body>
<?php
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>	<div<?php if ($_tmp = array_filter(['flash', $flash->type])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>><?php
			echo LR\Filters::escapeHtmlText($flash->message) /* line 13 */ ?></div>
<?php
			$iterations++;
		}
?>

    <div class="container"> 
        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">


                <div class="btn-group" role="group" aria-label="left">
                    <button class="btn btn-primary btn-sm"><a class='white' href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">Vozový park</a></button>
                    <button class="btn btn-secondary btn-sm"><a class='white' href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Vozy:novy")) ?>">Přidat vůz</a></button>
                    <button class="btn btn-primary btn-sm"><a class='white' href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Vodici:vodici")) ?>">Řidiči</a></button>
                    <button class="btn btn-secondary btn-sm"><a class="white" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Vodici:novyridic")) ?>">Přidat řidiče</a></button>
                </div>
              <button class="btn btn-primary btn-sm my-2 my-sm-0"><a class='white' href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">LOGOUT</a></button>
        </div>
        
<?php
		$this->renderBlock('content', $this->params, 'html');
?>

<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('scripts', get_defined_vars());
?>
</body>
</html>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (!$this->getReferringTemplate() || $this->getReferenceType() === "extends") {
			if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 13');
		}
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockScripts($_args)
	{
		extract($_args);
?>
        
    </div>    
        
	<script src="https://nette.github.io/resources/js/3/netteForms.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type='text/javascript' src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 40 */ ?>/js/upravy.js">
<?php
	}

}
