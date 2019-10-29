<?php
// source: C:\xampp\htdocs\Nette_databaze_vozu\app\Presenters/templates/Vozy/upravit.latte

use Latte\Runtime as LR;

class Template93511243d6 extends Latte\Runtime\Template
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
			if (isset($this->params['kus'])) trigger_error('Variable $kus overwritten in foreach on line 8');
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
}
<?php
		$iterations = 0;
		foreach ($vuz as $kus) {
			?>            <p><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Vozy:vuz", [$kus->vin])) ?>">← zpět na detail vozidla</a></p>
<?php
			$iterations++;
		}
?>
</div>

<div id="content">
        <div class="row">
            <h2><b>Vozidlo <?php echo LR\Filters::escapeHtmlText($kus->spz) /* line 17 */ ?> : <?php echo LR\Filters::escapeHtmlText($kus->vyrobce_vozu) /* line 17 */ ?> - <?php
		echo LR\Filters::escapeHtmlText($kus->model_vozu) /* line 17 */ ?> </b></h2>
        </div>
        <div class="row">
            <div class="col-2">
                
            </div>
            <div class="col-4">
                <p><b>Aktuální stav :</b></p>
            </div>
            <div class="col-5">
                <p><b> Upravit :</b></p>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>Oddělení : </td><td><?php echo LR\Filters::escapeHtmlText($kus->oddeleni) /* line 35 */ ?></td>
                        </tr>
                        <tr>
                            <td>Řidič : </td><td><?php echo LR\Filters::escapeHtmlText($kus->jmeno) /* line 38 */ ?> <?php
		echo LR\Filters::escapeHtmlText($kus->prijmeni) /* line 38 */ ?></td>
                        </tr>
                        <tr>
                            <td>Zapůjčeno : </td><td><?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->prirazeni_zacatek, 'Y-m-d')) /* line 41 */ ?></td>
                        </tr>
                        <tr>
                            <td>Poslední servis : </td><td><?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->posledni_servis, 'Y-m-d')) /* line 44 */ ?></td>
                        </tr>
                        <tr>
                            <td>Poslední STK : </td><td><?php echo LR\Filters::escapeHtmlText(($this->filters->date)($kus->posledni_stk, 'Y-m-d')) /* line 47 */ ?></td>
                        </tr>
                        <tr>
                            <td>VIN : </td><td><?php echo LR\Filters::escapeHtmlText($kus->vin) /* line 50 */ ?></td>
                        </tr>
                </table>
            </div>
            <div class="col-6">
<?php
		/* line 55 */ $_tmp = $this->global->uiControl->getComponent("vuzeditForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
		$_tmp->render();
?>
            </div>
        </div>
        
</div>


<?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?>	<h1>Editace vozidla</h1>
<?php
	}

}
