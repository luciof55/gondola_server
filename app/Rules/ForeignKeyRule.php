<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ForeignKeyRule implements Rule
{
    protected $repository;
	protected $data;
	protected $message;
	/**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($repository, $data)
    {
		Log::info('__construct ForeignKey Rule');
        $this->repository = $repository;
		$this->data = $data;
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
		Log::info('Execute ForeignKey Validataion: '. $value);
		if (method_exists($this->repository, 'canDelete')) {
			$command = $this->repository->find($value);
			$result = $this->repository->canDelete($command);
			$this->message = $result->get('message');
			return $result->get('status');
		} else {
			$command = $this->repository->find($value);
			if (method_exists($this->repository->entity(), 'canDelete')) {
				return $command->canDelete();
			} else {
				return true;
			}
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
        return 'Existen datos relacionados, no se puede eliminar.';
    }
}
