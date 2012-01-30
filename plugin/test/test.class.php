<?php
class Test {

	private $options;

	public function execute() {
		//echo "Naziv plugin-a: ".$options->opt_2;
	}

	public function __setOptions($optionObject) {
		$this -> options = $optionObject;

		echo "<pre>";
		var_dump($this -> options);
		echo "</pre>";
	}

}
?>