<?php
// 2026-10-24 06:11:05

/* PHP
Topic: PHP Generators (the yield keyword)

Explanation:
Generators allow you to create iterators without building an entire array in memory.  
When a function contains the keyword yield, it returns a Generator object that produces values one at a time.  
Each call to yield pauses the function, preserving its local state, and resumes from that point on the next iteration.  
This makes handling large data sets or streams much more memory‑efficient.  
Generators are especially useful for reading large files, processing database rows, or implementing infinite sequences.

Code Example:
<?php
// A simple generator that yields numbers from 1 up to $max
function getNumbers(int $max): Generator
{
    for ($i = 1; $i <= $max; $i++) {
        // yield returns the current number and pauses execution
        yield $i;
    }
}

// Using the generator in a foreach loop
foreach (getNumbers(5) as $number) {
    // Each $number is produced on demand, no array is stored
    echo $number . PHP_EOL;
}
?>
*/

/* Laravel
Laravel Service Container and Dependency Injection

Explanation:
The Laravel service container is a powerful tool that manages class dependencies and performs automatic injection. It resolves objects by reading type hints in constructors or method signatures, allowing you to decouple your code and follow the inversion of control principle. By binding interfaces to concrete implementations, you can easily swap implementations without changing the dependent code. The container also supports contextual binding, singleton bindings, and binding closures for complex object creation. Understanding the container is essential for writing clean, testable, and maintainable Laravel applications.

Code Example:
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;
use Illuminate\Http\Request;

// Service provider where the binding is defined
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to a concrete class; a new instance is created each time
        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);

        // Example of a singleton binding – the same instance is reused
        $this->app->singleton('logger', function ($app) {
            return new \Monolog\Logger('app');
        });
    }
}

// Define an interface for the payment gateway
interface PaymentGateway
{
    public function charge(float $amount, string $currency);
}

// Concrete implementation of the interface
class StripePaymentGateway implements PaymentGateway
{
    public function charge(float $amount, string $currency)
    {
        // Logic to interact with Stripe's API
        // This is just a placeholder for demonstration
        return "Charged {$amount} {$currency} via Stripe.";
    }
}

// Controller that receives the dependency automatically
class CheckoutController extends Controller
{
    protected $gateway;

    // Laravel injects the concrete class based on the binding above
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function process(Request $request)
    {
        $amount = $request->input('amount');
        $currency = $request->input('currency', 'USD');

        // Use the injected payment gateway to charge the customer
        $result = $this->gateway->charge($amount, $currency);

        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
Recursive CTEs enable you to work with hierarchical data, such as organizational charts or bill‑of‑materials.  
The WITH clause defines the CTE; the recursive part must contain a UNION ALL between an anchor query and a recursive query.  
Recursive CTEs execute repeatedly until the recursive query returns no rows, effectively walking the hierarchy.

Code example with comments:
-- Define a recursive CTE named employee_hierarchy
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: start with the top‑level manager (e.g., employee_id = 1)
    SELECT employee_id, manager_id, employee_name, 1 AS level
    FROM employees
    WHERE employee_id = 1
    UNION ALL
    -- Recursive member: find direct reports of the previous level
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1
    FROM employees e
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id
)
-- Use the CTE to retrieve the full hierarchy with levels
SELECT employee_id, manager_id, employee_name, level
FROM employee_hierarchy
ORDER BY level, employee_id;
*/

/* JavaScript
Topic: Asynchronous Programming with Promises and async/await  

Explanation:  
- JavaScript runs in a single thread, so long‑running operations (e.g., network requests) must be handled without blocking the event loop.  
- A Promise represents a value that may be available now, later, or never, and provides then and catch methods for chaining.  
- The async keyword turns a function into one that returns a Promise, allowing you to write asynchronous code that looks synchronous.  
- Inside an async function, the await keyword pauses execution until the awaited Promise resolves or rejects, simplifying error handling with try/catch.  
- Using async/await improves readability and maintainability compared to deeply nested then/catch chains.  

Code example (with comments):  
function fetchData(url) {  
    // Return a Promise that resolves with the fetched JSON data  
    return fetch(url).then(response => {  
        if (!response.ok) {  
            // Reject the Promise if the HTTP status is not OK  
            throw new Error(`Network error: ${response.status}`);  
        }  
        return response.json();  
    });  
}  

async function loadAndProcess() {  
    try {  
        // Await the Promise returned by fetchData; execution pauses here until it resolves  
        const data = await fetchData('https://api.example.com/items');  
        // Process the data after it has been retrieved  
        console.log('Received items:', data);  
    } catch (err) {  
        // Handle any errors from fetchData or from the await expression  
        console.error('Failed to load data:', err.message);  
    }  
}  

// Call the async function; it returns a Promise that we can ignore or handle further  
loadAndProcess();
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with GPT‑4  

Explanation:  
1. Few‑shot prompting supplies the model with a small number of example inputs and outputs within the same prompt, guiding it to infer the desired pattern.  
2. Effective prompt engineering balances clarity, brevity, and enough context so the model can generalize from the examples.  
3. Structure the prompt with a clear instruction, a separator, a few labeled examples, another separator, and finally the new query.  
4. Use consistent formatting (e.g., JSON, bullet points, or “Q:”/“A:” tags) to reduce ambiguity for the model.  
5. Adjust temperature, max_tokens, and stop sequences in the API call to control creativity and ensure the model stops after producing the answer.  

Code example (Python, OpenAI API) with comments:  

import os  
import openai  

# Load your OpenAI API key from environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def few_shot_completion(user_question):  
    # Define the prompt template with instruction, examples, and placeholder for new query  
    prompt = (  
        "You are an expert assistant that converts natural‑language math word problems into Python code.\n"  
        "### Examples ###\n"  
        "Q: What is the sum of the first 10 positive integers?\n"  
        "A: total = sum(range(1, 11))\n"  
        "Q: Calculate the factorial of 5.\n"  
        "A: import math\n"  
        "   result = math.factorial(5)\n"  
        "### End of examples ###\n"  
        "Q: " + user_question + "\n"  
        "A:"  
    )  

    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",                     # lightweight but capable model  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,                         # deterministic output for code generation  
        max_tokens=150,                          # enough space for a short code snippet  
        stop=["Q:", "###"]                       # stop when next example would start  
    )  

    # Extract the generated code from the assistant's reply  
    answer = response.choices[0].message.content.strip()  
    return answer  

# Example usage  
question = "Find the greatest common divisor of 48 and 180."  
generated_code = few_shot_completion(question)  
print("Generated Python code:\n", generated_code)  
*/

