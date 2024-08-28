<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2023. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://www.elastic.co/licensing/elastic-license
 */

namespace App\Http\ValidationRules\Company;

use App\Libraries\MultiDB;
use App\Utils\Ninja;
use Illuminate\Contracts\Validation\Rule;
use Symfony\Component\Validator\Constraints\EmailValidator;

/**
 * Class ValidCompanyQuantity.
 */
class ValidExpenseMailbox implements Rule
{

    private $validated_schema = false;
    private $isEnterprise = false;
    private array $endings;
    private bool $hasCompanyKey;
    private array $enterprise_endings;

    public function __construct()
    {
        $this->endings = explode(",", config('ninja.inbound_mailbox.expense_mailbox_endings'));
    }

    public function passes($attribute, $value)
    {
        if (empty($value)) {
            return true;
        }

        // early return, if we dont have any additional validation
        if (!config('ninja.inbound_mailbox.expense_mailbox_endings')) {
            $this->validated_schema = true;
            return MultiDB::checkExpenseMailboxAvailable($value);
        }

        // Validate Schema
        $validated = false;
        foreach ($this->endings as $ending) {
            if (str_ends_with($value, $ending)) {
                $validated = true;
                break;
            }
        }

        if (!$validated)
            return false;

        $this->validated_schema = true;
        return MultiDB::checkExpenseMailboxAvailable($value);
    }

    /**
     * @return string
     */
    public function message()
    {
        if (!$this->validated_schema)
            return ctrans('texts.expense_mailbox_invalid');

        return ctrans('texts.expense_mailbox_taken');
    }
}
