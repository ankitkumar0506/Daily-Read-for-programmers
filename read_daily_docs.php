<?php
// 2026-11-22 08:31:39

/* PHP
PHP Topic: Traits in PHP

Explanation:
Traits provide a mechanism for code reuse in single inheritance languages like PHP.  
A trait is similar to a class, but it is intended to group methods that can be used in multiple classes.  
Classes can include one or more traits using the `use` keyword, allowing them to inherit method implementations without extending a parent class.  
Traits can also define abstract methods that the using class must implement, ensuring required functionality.  
If two traits inserted into a class contain methods with the same name, conflicts can be resolved with the `insteadof` and `as` operators.

Code example (with comments):
<?php
// Define a trait that provides logging functionality
trait Logger {
    // Simple method to log a message with a timestamp
    public function log($message) {
        echo "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
    }
}

// Define another trait that adds a helper for formatting strings
trait StringHelper {
    // Method to convert a string to title case
    public function toTitleCase($str) {
        return ucwords(strtolower($str));
    }
}

// A class that uses both traits
class User {
    // Include the traits in this class
    use Logger, StringHelper;

    private $name;

    public function __construct($name) {
        $this->name = $name;
        // Use the log method from Logger trait
        $this->log("User object created.");
    }

    public function displayName() {
        // Use the toTitleCase method from StringHelper trait
        $formatted = $this->toTitleCase($this->name);
        $this->log("Displaying name: $formatted");
        echo $formatted . PHP_EOL;
    }
}

// Example usage
$user = new User("jane doe");
$user->displayName();
?>
*/

/* Laravel
Topic: Laravel Service Container and Automatic Dependency Injection

Explanation:  
The Service Container is Laravel’s powerful tool for managing class dependencies and performing dependency injection. It binds abstract types or interfaces to concrete implementations, allowing you to resolve them automatically. When a class’s constructor type‑hints a dependency, the container will inject an instance when the class is resolved, eliminating manual instantiation. This promotes loose coupling, easier testing, and clearer code organization. You can bind singletons, contextual bindings, or use auto‑resolution for classes that have no explicit bindings.

Code example with comments:

<?php
namespace App\Http\Controllers;

use App\Services\PaymentGatewayInterface;
use App\Services\StripePaymentGateway;
use Illuminate\Routing\Controller as BaseController;

// Bind the interface to a concrete class in a service provider (e.g., AppServiceProvider)
public function register()
{
    $this->app->bind(PaymentGatewayInterface::class, StripePaymentGateway::class);
}

// A simple controller that receives the dependency via constructor injection
class CheckoutController extends BaseController
{
    protected $paymentGateway;

    // Laravel automatically resolves the concrete implementation of PaymentGatewayInterface
    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    // Example action that uses the injected service
    public function process()
    {
        // The $paymentGateway instance is ready to use; no need to new it manually
        $result = $this->paymentGateway->charge(1500, 'usd', 'tok_visa');

        if ($result->successful()) {
            return response()->json(['status' => 'Payment successful']);
        }

        return response()->json(['status' => 'Payment failed'], 422);
    }
}

// Interface definition (app/Services/PaymentGatewayInterface.php)
interface PaymentGatewayInterface
{
    public function charge(int $amount, string $currency, string $source);
}

// Concrete implementation (app/Services/StripePaymentGateway.php)
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGatewayInterface
{
    protected $stripe;

    public function __construct()
    {
        // Initialize Stripe client with secret key from config
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function charge(int $amount, string $currency, string $source)
    {
        // Create a charge using Stripe's SDK
        return $this->stripe->charges->create([
            'amount'   => $amount,
            'currency' => $currency,
            'source'   => $source,
        ]);
    }
}
?>
*/

/* MySQL
Topic: MySQL Stored Procedures with Input and Output Parameters

Explanation:  
A stored procedure is a precompiled set of one or more SQL statements stored under a name in the database. It can accept input parameters, return output parameters, and encapsulate complex logic that can be reused by applications. Using procedures improves performance because the execution plan is cached, and it centralizes business rules in the database layer. Input parameters allow callers to pass values, while output parameters let the procedure send results back without using result sets. Proper error handling inside a procedure (e.g., using DECLARE ... HANDLER) makes the code robust and easier to maintain.

Code example (with inline comments):

CREATE PROCEDURE GetEmployeeStats(
    IN p_dept_id INT,               -- input: department to filter
    OUT p_total INT,                -- output: total number of employees
    OUT p_avg_salary DECIMAL(10,2)  -- output: average salary in the department
)
BEGIN
    -- Initialize output variables in case the query returns no rows
    SET p_total = 0;
    SET p_avg_salary = 0.00;

    -- Compute total employees and average salary for the given department
    SELECT 
        COUNT(*) INTO p_total,
        AVG(salary) INTO p_avg_salary
    FROM employees
    WHERE department_id = p_dept_id;

    -- If no employees found, ensure defaults remain 0
    IF p_total IS NULL THEN
        SET p_total = 0;
        SET p_avg_salary = 0.00;
    END IF;
END;

-- Example call:
CALL GetEmployeeStats(5, @emp_count, @avg_sal);
SELECT @emp_count AS total_employees, @avg_sal AS average_salary;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
Closures are created automatically whenever a function is defined inside another function and the inner function references variables from the outer scope.  
They are useful for data privacy, function factories, and maintaining state across multiple calls without using global variables.  
Because the inner function holds a reference to the outer variables, those variables are not garbage‑collected until the closure itself is no longer reachable.  
Understanding closures helps avoid common pitfalls such as unintentionally sharing mutable state between iterations of a loop.

Code example:
// outer function returns an inner function that remembers 'counter'
function createCounter(initialValue) {
    let counter = initialValue;               // variable in the outer scope

    // inner function forms a closure over 'counter'
    return function() {
        counter += 1;                         // can modify 'counter' each call
        return counter;
    };
}

// using the closure
const myCounter = createCounter(5);          // myCounter is a function
console.log(myCounter()); // 6
console.log(myCounter()); // 7
console.log(myCounter()); // 8

// each call to createCounter produces an independent closure
const anotherCounter = createCounter(100);
console.log(anotherCounter()); // 101
console.log(myCounter());      // 9 (original closure retains its own state)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies a small number of example interactions inside the prompt to steer the model’s behavior without fine‑tuning. By framing a clear instruction followed by representative Q&A pairs, the model learns the desired pattern and can generalize to new inputs. This technique works well for tasks such as data extraction, text classification, or code generation where a full dataset for training is unavailable. The examples should be concise, varied enough to cover edge cases, and placed after the instruction but before the user’s actual query. Adjusting temperature and max_tokens further refines the output consistency.

Code example (Python, using the openai library):

import os
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt that teaches the model to extract email addresses
system_prompt = "You are an assistant that extracts email addresses from a given sentence."
few_shot_examples = [
    {"role": "user", "content": "Contact John at john.doe@example.com for more info."},
    {"role": "assistant", "content": "john.doe@example.com"},
    {"role": "user", "content": "Please reach out to support@myservice.io regarding the issue."},
    {"role": "assistant", "content": "support@myservice.io"},
]

# The new query we want the model to answer
new_query = {"role": "user", "content": "Send the report to alice.smith@company.org and cc bob@team.net."}

# Assemble the full message list
messages = [{"role": "system", "content": system_prompt}] + few_shot_examples + [new_query]

# Call the Chat Completion endpoint
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # choose a suitable model
    messages=messages,
    temperature=0.0,              # deterministic output for extraction tasks
    max_tokens=50,                # limit to short answer
)

# Print the extracted email addresses (the model may return them as a comma‑separated string)
print("Extracted emails:", response.choices[0].message.content.strip())
*/

