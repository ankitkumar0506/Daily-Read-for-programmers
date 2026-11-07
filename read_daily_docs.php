<?php
// 2026-11-07 07:31:52

/* PHP
Topic: PHP Generators

Explanation:
Generators allow a function to yield values one at a time instead of building a full array in memory.  
When the function encounters the `yield` keyword, execution pauses and the yielded value is returned to the caller.  
On the next iteration the function resumes right after the `yield`, preserving its local state.  
This makes it ideal for processing large data sets, streams, or infinite sequences with low memory overhead.  
Generators are created using a normal function declaration with one or more `yield` statements inside.

Code example (with comments):

function getNumbers($limit) {
    // Loop from 1 up to the specified limit
    for ($i = 1; $i <= $limit; $i++) {
        // Yield the current number and pause execution
        yield $i;
        // Execution will resume here on the next iteration
    }
}

// Using the generator
$generator = getNumbers(5);   // Creates a generator object, no numbers are produced yet

foreach ($generator as $number) {
    // Each iteration pulls the next yielded value
    echo "Number: $number\n";
}

// Output:
// Number: 1
// Number: 2
// Number: 3
// Number: 4
// Number: 5
*/

/* Laravel
Topic: Laravel Service Container and Dependency Injection

Explanation:
The Laravel service container is a powerful tool that manages class dependencies and performs automatic injection. It resolves objects by reading type hints in constructors or method signatures, allowing you to obtain fully built instances without manual new statements. By binding interfaces to concrete classes, you can swap implementations easily, which promotes a clean, testable architecture. The container also supports contextual binding, singleton bindings, and deferred service providers. Understanding and using the container effectively reduces boilerplate and decouples your code.

Code example (PHP):

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;          // Interface
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// The controller receives a concrete implementation of PaymentGateway automatically.
class CheckoutController extends Controller
{
    protected $paymentGateway;

    // Laravel injects the appropriate implementation here.
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    // Example action that uses the injected service.
    public function store(Request $request)
    {
        $amount = $request->input('amount');

        // The payment gateway handles the transaction.
        $result = $this->paymentGateway->charge($amount);

        if ($result->successful()) {
            return response()->json(['status' => 'Payment successful']);
        }

        return response()->json(['status' => 'Payment failed'], 422);
    }
}

// In a service provider you bind the interface to a concrete class.
namespace App\Providers;

use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the Stripe implementation as a singleton.
        $this->app->singleton(PaymentGateway::class, function ($app) {
            return new StripePaymentGateway(config('services.stripe.secret'));
        });
    }
}

// The concrete implementation.
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    // Example method required by the interface.
    public function charge($amount)
    {
        // Here you would call Stripe's SDK; this is a placeholder.
        return (object) ['successful' => true];
    }
}

// Interface definition.
namespace App\Contracts;

interface PaymentGateway
{
    public function charge($amount);
}
?>
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTEs) in MySQL

Explanation:
Recursive CTEs allow you to query hierarchical or tree‑structured data without needing stored procedures.  
They consist of an anchor query (the starting rows) and a recursive member that references the CTE itself.  
Each iteration adds a new level to the result set until the recursive member returns no rows.  
MySQL supports recursive CTEs from version 8.0 onward, enabling tasks such as building an employee‑manager chain or traversing category trees.  
Be sure to include the optional MAXRECURSION hint or a LIMIT clause to prevent infinite loops.

Code example (employee hierarchy):
-- Anchor part: select top‑level managers (no manager_id)
WITH RECURSIVE emp_hierarchy AS (
    SELECT
        employee_id,
        employee_name,
        manager_id,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive part: join employees to their manager from the previous level
    SELECT
        e.employee_id,
        e.employee_name,
        e.manager_id,
        eh.level + 1
    FROM employees e
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.employee_id
    WHERE eh.level < 10   -- safety limit to avoid endless recursion
)
SELECT
    employee_id,
    employee_name,
    manager_id,
    level
FROM emp_hierarchy
ORDER BY level, manager_id;   -- shows the hierarchy from top to bottom.
*/

/* JavaScript
Topic: The JavaScript Event Loop and Microtasks

Explanation:
The event loop is the mechanism that allows JavaScript to perform non‑blocking operations despite being single‑threaded.  
When the call stack is empty, the event loop checks the task queue for macrotasks (e.g., setTimeout, I/O callbacks).  
Microtasks, such as Promise callbacks and queueMicrotask, have higher priority and are drained before the next macrotask.  
Understanding the order in which macrotasks and microtasks run is crucial for writing predictable asynchronous code.  
Misplaced microtasks can lead to subtle bugs, especially when mixing async/await with timers or DOM events.  

Code example (with comments):
function log(message) {
    console.log(message);
}

log('Start');                     // 1. Synchronous log

setTimeout(() => {
    log('Macrotask: setTimeout'); // 5. Runs after all microtasks are processed
}, 0);

Promise.resolve().then(() => {
    log('Microtask: first then'); // 3. Microtasks run before the next macrotask
    return Promise.resolve();
}).then(() => {
    log('Microtask: second then'); // 4. Chained microtask, also before the macrotask
});

log('End');                       // 2. Synchronous log, completes before any async callbacks

// Expected console order:
// Start
// End
// Microtask: first then
// Microtask: second then
// Macrotask: setTimeout
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of example input‑output pairs before the actual user query, guiding it toward the desired format and style. By placing clear demonstrations in the system or user messages, the model can infer the task without needing extensive fine‑tuning. This technique works well for classification, data extraction, and code generation where a consistent output schema is required. The examples should be concise, representative, and separated from the real request to avoid confusion. Adjust the temperature to 0 for deterministic results when the task is deterministic.

Code example (Python, using openai library)  

import openai  

# Your OpenAI API key – set as an environment variable or replace here  
openai.api_key = "sk-YOUR_API_KEY"  

# Define a few‑shot prompt: two examples of extracting email addresses from text  
messages = [  
    { "role": "system", "content": "You are an assistant that extracts email addresses from a given paragraph and returns them as a JSON list." },  
    { "role": "user", "content": "Paragraph: \"Contact us at support@example.com or sales@company.org for assistance.\"" },  
    { "role": "assistant", "content": "{\"emails\": [\"support@example.com\", \"sales@company.org\"]}" },  
    { "role": "user", "content": "Paragraph: \"Reach out to jane.doe@xyz.com for more info.\"" },  
    { "role": "assistant", "content": "{\"emails\": [\"jane.doe@xyz.com\"]}" },  
    # Real query – no answer yet, model should follow the pattern above  
    { "role": "user", "content": "Paragraph: \"Our team can be reached at dev@tech.io, info@tech.io, and admin@tech.io.\"" }  
]  

response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",        # or any ChatCompletion model you have access to  
    messages=messages,  
    temperature=0,               # deterministic output for extraction tasks  
    max_tokens=150  
)  

# Print the model's JSON‑formatted answer  
print(response.choices[0].message.content)  
*/

