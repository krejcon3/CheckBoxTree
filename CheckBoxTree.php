<?php
/*
 * Copyright (c) 2014, Ondřej Krejčíř
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *	  * Redistributions of source code must retain the above copyright
 * 		notice, this list of conditions and the following disclaimer.
 * 	  * Redistributions in binary form must reproduce the above copyright
 * 		notice, this list of conditions and the following disclaimer in the
 *		documentation and/or other materials provided with the distribution.
 *	  * Neither the name of the Ondřej Krejčíř nor the
 * 		names of its contributors may be used to endorse or promote products
 * 		derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL Ondřej Krejčíř BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace krejcon3;

use Nette,
	Nette\Utils\Html,
	Nette\Forms\Container;


class CheckboxTree extends Nette\Forms\Controls\MultiChoiceControl {

	public function __construct($label = NULL, array $items = NULL) {
		parent::__construct($label, $items);
		$this->control->type = 'checkbox';
	}

	public function getControl() {
		return $this->recursiveRender($this->getItems());
	}

	private function recursiveRender($list) {
		$html = Html::el("ul");
		foreach ($list as $key => $value) {
			if (is_array($value)) {
				$html->add(Html::el("li")->add($this->recursiveRender($value)));
			} else {
				$html->add(Html::el("li")
						->add($this->getControlPart($key))
						->add(Html::el("label", array("for" => $this->getHtmlId() . '-' . $key))
								->add($value)
						)
				);
			}
		}
		return $html;
	}

	public function getLabel($caption = NULL) {
		return parent::getLabel($caption)->for(NULL);
	}

	public function getControlPart($key) {
		return parent::getControl()->addAttributes(array(
			'id' => $this->getHtmlId() . '-' . $key,
			'checked' => in_array($key, (array)$this->value),
			'disabled' => is_array($this->disabled) ? isset($this->disabled[$key]) : $this->disabled,
			'required' => NULL,
			'value' => $key,
		));
	}

	public function getLabelPart($key) {
		return parent::getLabel($this->items[$key])->for($this->getHtmlId() . '-' . $key);
	}

	public function getSelectedItems() {
		return array_intersect_key($this->recursiveJoin($this->items), array_flip($this->value));
	}

	public function getValue() {
		return array_values(array_intersect($this->value, array_keys($this->recursiveJoin($this->items))));
	}

	private function recursiveJoin(array $array, $arry = array()) {
		foreach ($array as $key => $val) {
			if (is_array($val)) {
				$arry = $this->recursiveJoin($val, $arry);
			} else {
				$arry[$key] = $val;
			}
		}
		return $arry;
	}

	public static function register()
	{
		Container::extensionMethod('addCheckboxTree', function (Container $_this, $name, $label, array $items = NULL) {
			return $_this[$name] = new CheckboxTree($label, $items);
		});
	}
}
