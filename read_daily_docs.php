<?php
// 2026-09-21 06:45:50

/* PHP
PHP Generators (Yield)

Explanation:
- Generators provide a simple way to implement iterators without the overhead of building a full iterator class.
- By using the `yield` keyword, a function can return values one at a time, pausing its execution state between each yield.
- This approach reduces memory consumption, especially when dealing with large data sets or infinite sequences.
- Generators are lazy; they produce each value only when requested by the consumer.
- They can also receive input via `send()` and return a final value with `return` in PHP 7+.

Code Example:
// A generator function that yields the first n Fibonacci numbers
function fibonacciGenerator(int $limit): Generator {
    $a = 0;
    $b = 1;
    $count = 0;

    while ($count < $limit) {
        // Yield the current value and pause execution
        yield $a;
        // Calculate next Fibonacci number
        $temp = $a + $b;
        $a = $b;
        $b = $temp;
        $count++;
    }

    // Optional final return value (available via getReturn())
    return "Generated $limit numbers";
}

// Using the generator
$limit = 10;
$gen = fibonacciGenerator($limit);

foreach ($gen as $index => $value) {
    echo "Fib[$index] = $value\n";
}

// Retrieve the generator's return value (available in PHP 7+)
$finalMessage = $gen->getReturn();
echo $finalMessage . "\n";
*/

/* Laravel
Laravel Service Container & Dependency Injection  

The service container is Laravel’s powerful IoC (Inversion of Control) manager that resolves class dependencies automatically. By binding abstractions (interfaces) to concrete implementations, you decouple code and make it easier to test. Dependency injection lets you type‑hint required services in constructors or methods, and the container provides the appropriate instances. This pattern promotes single responsibility and keeps controllers thin. You can bind singletons, contextual bindings, or use automatic resolution for classes without explicit bindings.

Example – a simple payment service bound in the container and injected into a controller:

// app/Contracts/PaymentGateway.php
<?php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount);
}

// app/Services/StripePaymentGateway.php
<?php
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    // Here you would inject Stripe SDK client if needed
    public function charge(float $amount)
    {
        // Simulated charge logic
        return "Charged $$amount using Stripe.";
    }
}

// app/Providers/AppServiceProvider.php
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to its concrete implementation
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
    }

    public function boot()
    {
        //
    }
}

// app/Http/Controllers/OrderController.php
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The container injects the concrete StripePaymentGateway automatically
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $amount = $request->input('amount');

        // Use the injected service to process the payment
        $result = $this->paymentGateway->charge($amount);

        return response()->json(['message' => $result]);
    }
}
*/

/* MySQL
MySQL Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
- A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.
- Defined using the WITH clause, a CTE improves readability and allows you to break complex queries into logical building blocks.
- CTEs can be recursive, enabling hierarchical data traversal such as organizational charts or category trees.
- In MySQL 8.0 and later, multiple CTEs can be defined in a single WITH clause, separated by commas.
- Recursive CTEs require an anchor member (the base case) and a recursive member that references the CTE itself, plus a termination condition to avoid infinite loops.

Code Example (finding all sub‑categories of a given category using a recursive CTE):
-- Define the CTE named "category_hierarchy"
WITH RECURSIVE category_hierarchy AS (
    -- Anchor member: start with the root category (e.g., id = 1)
    SELECT id, name, parent_id, 0 AS level
    FROM categories
    WHERE id = 1
    UNION ALL
    -- Recursive member: find children of the categories already in the hierarchy
    SELECT c.id, c.name, c.parent_id, ch.level + 1
    FROM categories c
    INNER JOIN category_hierarchy ch ON c.parent_id = ch.id
)
-- Use the CTE to retrieve the full hierarchy ordered by level
SELECT id, name, parent_id, level
FROM category_hierarchy
ORDER BY level, name;
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
Closures enable data privacy, allowing you to expose only selected functionality while keeping internal state hidden.  
They are created every time a function is defined, capturing the surrounding lexical environment at that moment.  
Common use‑cases include function factories, memoization, and implementing private variables in objects.  
Understanding closures is essential for mastering asynchronous patterns and module design in JavaScript.

Code example with comments:

function makeCounter() {                // outer function creates a private variable
    let count = 0;                     // this variable is scoped to makeCounter
    return function() {                // inner function forms a closure over count
        count++;                       // can modify the private count variable
        console.log('Current count:', count);
    };
}

const counterA = makeCounter();         // each call gets its own independent closure
const counterB = makeCounter();

counterA(); // Output: Current count: 1
counterA(); // Output: Current count: 2
counterB(); // Output: Current count: 1   (separate closure, independent state)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies a small set of example interactions within the prompt to guide the model’s behavior without fine‑tuning. By framing the task with a few input‑output pairs, the model can infer the desired pattern and apply it to new inputs. This technique works well for classification, transformation, or extraction tasks where a full dataset is unavailable. The examples must be clear, consistent, and as close as possible to the target domain. Adjusting temperature and max_tokens helps balance creativity and deterministic output.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt that teaches the model to convert sentences to passive voice  
few_shot_prompt = """Convert the following active‑voice sentences to passive voice.

Active: The chef cooked the meal.  
Passive: The meal was cooked by the chef.

Active: The student solved the problem.  
Passive: The problem was solved by the student.

Active: {input_sentence}
Passive:"""  

def convert_to_passive(sentence: str) -> str:  
    # Insert the user sentence into the prompt template  
    prompt = few_shot_prompt.format(input_sentence=sentence)  

    # Call the Chat Completion endpoint with a deterministic temperature  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,          # low temperature for consistent output  
        max_tokens=60,            # enough for a short sentence  
        n=1,  
    )  

    # Extract the generated passive sentence from the response  
    passive = response.choices[0].message.content.strip()  
    return passive  

# Example usage  
if __name__ == "__main__":  
    active_sentence = "The engineer designed the circuit."  
    passive_sentence = convert_to_passive(active_sentence)  
    print("Active:", active_sentence)  
    print("Passive:", passive_sentence)  
*/

