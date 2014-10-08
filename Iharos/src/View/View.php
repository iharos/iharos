<?php
// /Iharos/src/View/View.php

/* Template subsystem for the Iharos framework.
 * Inspired by Chris Hayes' Scenic Views: http://chrishayes.ca/blog/tag/template-inheritance
 *
 * The View class extends the basic templating capabilities of PHP with
 * template inheritance and extensibility.
 *
 * The input data is an associative array keyed by strings. The keys provide
 * the variable names, the values are the variable values in the template.
 * Templates are arbitrary files (XML, RSS/Atom, HTML, etc.) with .php extension.
 * The output is a string.
 *
 * The View class provides template inheritance. Templates can extend other templates,
 * this is implemented as cascading template loading. Template files are queued in the
 * $this->queue class property, the earliest item in the queue is always the one
 * to be processed.
 *
 * The templates are extensible via blocks, which are overwritable sections.
 * When a block is encountered, its contents are extracted from the PHP buffer.
 * Blocks are processed from the latest to the first (just as the template files),
 * therefore the first encountered block will overwrite every higher level block.
 *
 * Available tools in the templates:
 * - standard PHP code
 * - variables: keys of the input data as variable names
 * - $extend(): extend another template file with the current one
 * - $block(): starting point of a block
 * - $show_block(): display a block
 * - $end(): 
 *
 * Example:
 * layout.template.php:
 * 		<html>
 * 			<head><?php $show_block('title') ?> Default title <?php $end() ?></head>
 * 			<body></body>
 * 		</html>
 * page.template.php
 * <?php $extend('layout.template.php') ?>
 * <?php $block('title') ?> This is the module-specific title: <?= $title ?><?php $end() ?>
*/

namespace Iharos\View;

class View {
	/* The combined (global) template data as an associative array
	 * @var $vars array */
	protected $vars;
	
	/* The number of variables from the input data (keys)
	 * @var $vars_count int */
	protected $vars_count;
	
	/* The buffer of replacable blocks
	 * @var $blocks array */
	protected $blocks;
	
	/* The stack of the buffer names to determine which one is a keeper
	 * @var $block_stack array */
	protected $block_stack;
	
	/* The queue of templates files, each value is a template file name
	 * @var $queue array */
	private $queue;
	
	
	/* Render the template cascade.
	 * The method binds the input data ($data) to the current View instance and
	 * starts the loading cascade of one or more templates.
	 *
	 * @var $tpl_file string The path of the template file to load
	 * @var $data array the associative array of input data, values keyed by future variable names*/
	public function render($tpl_file, $data)
	{
		$this->vars = $data;
		$this->vars_count = count($data);
		return $this->load($tpl_file);
	}


	/* Load the cascading template files and extract the input data as variables. */
	protected function load($tpl_file)
	{
		if ($this->vars === null) { // can be an empty array()
			throw new \Exception("Template keys were not found. No data?");
		}
		
		$tpl_file = realpath($tpl_file);
		
		$extr_count = extract($this->vars, EXTR_REFS);
		if ($extr_count !== count($this->vars)) {
			throw new \Exception("The number of extracted template keys ('"
				.$extr_count
				."') does not match the number of keys ('"
				.$this->vars_count
				."'). Missing, overwritten or bad key name? Malformed template array?");
		}
		
		$T = $this;
		$data =& $this->vars;
		
		$extends = function($tplf) use ($T) {
			$T->enQueue($tplf);
		};
		
		$block = function($name) use ($T) {
			echo $T->block($name);
		};
		
		$show = function($name) use ($T) {
			echo $T->displayBlock($name);
		};
		
		$end = function() use ($T) {
			$T->endBlock();
		};
		
		if (!file_exists($tpl_file)) {
			throw new \Exception("Template file '$tpl_file' is not found.");
		}

		ob_start();
		include($tpl_file);
		echo $this->processQueue();
		
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}

	
	/* Queue another template file for processing. */
	protected function enQueue($tpl_file)
	{
		$this->queue[] = $tpl_file;
	}

	
	/* Process the next file in the template queue. */
	protected function processQueue()
	{
		$tpl_file = array_shift($this->queue);

		if ($tpl_file) {
			return $this->load($tpl_file);
		} else {
			// if queue is empty (no template to process), do nothing
			return null;
		}
	}
	
	
	/* Add a template file to the processing queue. */
	protected function extend($tpl_file)
	{
		$this->queue[] = realpath($tpl_file);
	}

	
	/* Add a block to the block stack if it doesn't exist. */
	protected function block($name)
	{
		if (!isset($this->blocks[$name])) {
			$this->block_stack[] = $name;
			ob_start();
		}
	}

	
	/* Echo the contents of a block. */
	protected function displayBlock($name)
	{
		echo $this->blocks[$name];
		ob_start(); // start the PHP buffer to discard later the default content of the highest level block
	}

	
	/* Get the contents of a block and save it to the block buffer or discard it
	 * if it already exists. */
	protected function endBlock()
	{
		$open_block = array_pop($this->block_stack); // last opened block
		
		if (isset($open_block)) {
			if (!isset($this->blocks[$open_block])) {
				$this->blocks[$open_block] = ob_get_contents();
			}
		}
		
		// current output buffer may have been opened in displayBlock()
		// this just discards it
		ob_end_clean();
	}

	
	/* Check if a key exists in the input data. */
	public function exists($key)
	{
		return isset($this->vars[$key]);
	}

	
	/* Add a key to the input data. */
	public function set($key, $value = null, $overwrite = true)
	{
		if (is_array($key) && $value === null) {
			$this->vars = array_merge($this->vars, $key);
		} else {
			if (!isset($this->vars[$key]) or $overwrite) {
				$this->vars[$key] = $value;
			} else {
				throw new \Exception("Template key '$key' already exists.");
			}
		}
		
		return $this;
	}

	
	/* Get a key from the input data. */
	public function get($key)
	{
		return $this->vars[$key];
	}
	
	
}