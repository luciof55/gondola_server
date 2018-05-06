<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class RestoreRule implements Rule
{
    protected $repository;
	protected $command;
	protected $data;
	protected $message;
	/**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($repository, $data, $command)
    {
		Log::info('__construct Restore Rule');
        $this->repository = $repository;
		$this->data = $data;
		$this->command = $command;
    }
	
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
		Log::info('Execute Restore Validataion: '. $value);
		if (method_exists($this->repository, 'canRestore')) {
			$result = $this->repository->canRestore($this->command, $this->message);
			$this->message = $result->get('message');
			return $result->get('status');
		} else {
			return true;
		}
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
		if (isset($this->message)) {
			return $this->message;
		}
        return 'No se puede habilitar existen relaciones que no están habilitadas.';
    }
}
