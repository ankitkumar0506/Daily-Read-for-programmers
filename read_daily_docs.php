<?php
// 2026-11-25 10:07:47

/* PHP
Topic: PHP Generators for Memory‑Efficient Iteration  

Explanation:  
Generators let a function yield values one at a time instead of building a complete array in memory.  
Each call to the generator’s next() method resumes execution right after the previous yield statement.  
They are ideal for processing large data sets, reading big files, or streaming results from a database query.  
Because only a single value is kept in memory at any moment, memory consumption stays low even with millions of items.  
Generators also make the code more readable than manual iterator classes, while still supporting foreach loops.  

Code example (with comments):  

<?php
// A generator that yields each line of a large text file
function readFileLines(string $filePath): Generator
{
    $handle = fopen($filePath, 'r');
    if ($handle === false) {
        throw new RuntimeException("Unable to open file: $filePath");
    }

    try {
        // Loop until end‑of‑file, yielding one line per iteration
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($line !== false) {
                // Trim the newline and yield the line to the caller
                yield rtrim($line, "\r\n");
            }
        }
    } finally {
        // Ensure the file is always closed, even if the caller breaks out early
        fclose($handle);
    }
}

// Using the generator in a foreach loop
foreach (readFileLines('large_log.txt') as $lineNumber => $text) {
    // Process each line without loading the whole file into memory
    echo "Line " . ($lineNumber + 1) . ": " . $text . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Service Container & Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs automatic injection. It resolves objects by inspecting their constructors and provides the appropriate instances, allowing you to write loosely coupled code. By binding interfaces to concrete implementations, you can swap out classes without changing the consuming code. Dependency injection can be performed in controllers, jobs, listeners, or any class resolved by the container. This promotes testability, as you can mock dependencies easily during unit tests.

Code example (binding an interface and injecting it into a controller):

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGateway contract to the Stripe implementation
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You could pull configuration values here if needed
            return new StripePaymentGateway(config('services.stripe.secret'));
        });
    }
}

// ---------------------------------------------------------

<?php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount, string $currency, string $source): bool;
}

// ---------------------------------------------------------

<?php
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    protected $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
        // Initialize Stripe client here if desired
    }

    public function charge(float $amount, string $currency, string $source): bool
    {
        // Simplified example of a charge request
        // In real code you would call Stripe's SDK
        // return Stripe::charges()->create([...]);
        return true;
    }
}

// ---------------------------------------------------------

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $paymentGateway;

    // The PaymentGateway dependency is automatically injected by the container
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function process(Request $request)
    {
        $amount   = $request->input('amount');
        $currency = $request->input('currency');
        $source   = $request->input('source');

        // Use the injected payment gateway to charge the customer
        $success = $this->paymentGateway->charge($amount, $currency, $source);

        return $success ? response('Payment successful', 200) : response('Payment failed', 400);
    }
}
?>
*/

/* MySQL
Topic: MySQL Indexes – Types and Usage  

Explanation:  
Indexes are data structures that improve the speed of data retrieval operations on a table at the cost of additional storage and slower write performance. MySQL supports several index types, the most common being PRIMARY KEY, UNIQUE, and NON‑UNIQUE (regular) indexes. A PRIMARY KEY is a unique index that also enforces NOT NULL and uniquely identifies each row. UNIQUE indexes guarantee that the indexed column(s) contain distinct values, while regular indexes simply speed up lookups without uniqueness constraints. Choosing the right index type and columns is critical for query optimization, especially for large tables or frequently joined columns.

Code example (with comments):  
CREATE TABLE customers (  
    customer_id INT AUTO_INCREMENT,  
    email VARCHAR(255) NOT NULL,  
    last_name VARCHAR(100),  
    city VARCHAR(100),  
    PRIMARY KEY (customer_id)               -- primary key index, unique and clustered  
);  

-- Create a UNIQUE index on the email column to prevent duplicate email addresses and speed up lookups by email  
CREATE UNIQUE INDEX idx_email_unique ON customers (email);  

-- Create a regular (non‑unique) index on the last_name column to accelerate searches by last name  
CREATE INDEX idx_last_name ON customers (last_name);  

-- Create a composite index on (city, last_name) to optimize queries that filter by city and then order by last name  
CREATE INDEX idx_city_lastname ON customers (city, last_name);  

-- Example query that will benefit from the composite index above  
EXPLAIN SELECT * FROM customers WHERE city = 'New York' ORDER BY last_name LIMIT 10;   -- the optimizer can use idx_city_lastname to locate rows efficiently.
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
- A closure is a function that retains access to its lexical scope even when executed outside that scope.  
- It allows inner functions to remember and use variables from an outer function after the outer function has finished.  
- Closures are created every time a function is defined, enabling data encapsulation and private state.  
- They are widely used for callbacks, event handlers, and module patterns.  
- Understanding closures helps avoid common pitfalls such as unintended variable sharing in loops.  

Code example:  
function makeCounter() {                     // outer function creates a private variable  
    let count = 0;                           // this variable is not directly accessible from outside  

    return function() {                      // inner function forms a closure over 'count'  
        count += 1;                          // modifies the private variable  
        console.log('Current count:', count);  
    };                                       
}                                            // end of makeCounter  

const counterA = makeCounter();   // each call creates a separate closure with its own 'count'  
const counterB = makeCounter();  

counterA(); // Current count: 1  
counterA(); // Current count: 2  
counterB(); // Current count: 1   (independent of counterA)  

// The inner function retains access to 'count' even after makeCounter() has returned.  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies a small number of example interactions to guide the model’s behavior without any fine‑tuning. By embedding these examples directly in the system or user messages, you can shape the response format, style, or domain knowledge. This technique works well for tasks such as data extraction, code generation, or role‑play scenarios where the model must follow a pattern. The examples are placed in the same message list that you send to the API, allowing the model to infer the desired output from the context. It is lightweight, requires no additional training, and can be adjusted on the fly.

Code example (Python, using the openai library):

import os
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt that teaches the model to extract city names from sentences
messages = [
    {"role": "system", "content": "You are an assistant that extracts the name of the city mentioned in a user’s sentence and returns it as plain text."},
    {"role": "user", "content": "I will be traveling from Paris to Berlin next week."},
    {"role": "assistant", "content": "Berlin"},
    {"role": "user", "content": "My next vacation will be in Kyoto."},
    {"role": "assistant", "content": "Kyoto"},
    # New query for which the model should apply the learned pattern
    {"role": "user", "content": "We are planning a trip to Cape Town this summer."}
]

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",      # choose a suitable model
    messages=messages,
    temperature=0.0           # deterministic output for extraction tasks
)

# The model’s reply is in response['choices'][0]['message']['content']
city = response["choices"][0]["message"]["content"].strip()
print("Extracted city:", city)   # Expected output: Cape Town

# End of example. This pattern can be extended with more examples or different tasks by changing the system instruction and the demonstration pairs.
*/

