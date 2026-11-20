<?php
// 2026-11-20 08:51:28

/* PHP
Topic: Generators in PHP

Explanation:  
Generators allow you to create iterators without building an entire class that implements the Iterator interface. They use the yield keyword to produce values one at a time, keeping the function's state between calls. This reduces memory consumption because the whole dataset does not need to be stored in an array. Generators are especially useful for processing large data streams, such as reading big files or database rows. They also make code that deals with lazy sequences more readable and maintainable.

Code example with comments:  

<?php
// A simple generator that yields numbers from 1 up to a given limit
function rangeGenerator(int $max): Generator {
    for ($i = 1; $i <= $max; $i++) {
        // yield pauses the function and returns the current value
        yield $i;
    }
}

// Using the generator in a foreach loop
foreach (rangeGenerator(5) as $number) {
    // Each iteration receives the next value from the generator
    echo "Number: $number\n";
}

// Demonstrating that the generator is lazy – it does not build an array
function readLines(string $filePath): Generator {
    $handle = fopen($filePath, 'r');
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            // Yield each line as it is read from the file
            yield rtrim($line, "\r\n");
        }
        fclose($handle);
    } else {
        throw new RuntimeException("Cannot open file: $filePath");
    }
}

// Example usage (assumes 'large.txt' exists)
// foreach (readLines('large.txt') as $line) {
//     // Process each line without loading the whole file into memory
//     echo $line . PHP_EOL;
// }
?>
*/

/* Laravel
Topic: Service Container & Dependency Injection in Laravel

Explanation:
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection. It resolves objects automatically, allowing you to type‑hint classes in constructors or controller methods. By binding interfaces to concrete implementations, you can swap out functionality without changing the consuming code. The container also supports singleton bindings for shared instances and contextual bindings for specific use cases. Using the container makes your application more modular, testable, and adheres to the SOLID principles.

Code Example (app/Providers/AppServiceProvider.php):
public function register()
{
    // Bind an interface to a concrete class so the container can resolve it
    $this->app->bind(
        App\Contracts\PaymentGateway::class,
        App\Services\StripePaymentGateway::class
    );

    // Register a singleton that should be shared across the application
    $this->app->singleton(
        App\Services\ReportGenerator::class,
        function ($app) {
            // The container can resolve dependencies of ReportGenerator automatically
            return new App\Services\ReportGenerator($app->make(App\Repositories\ReportRepository::class));
        }
    );
}

// Example of automatic injection in a controller (app/Http/Controllers/OrderController.php)
use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // The container injects the concrete StripePaymentGateway implementation here
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        // Use the injected payment gateway to process a payment
        $this->paymentGateway->charge($request->input('amount'), $request->input('token'));

        // Additional order logic...
        return response()->json(['status' => 'success']);
    }
}
*/

/* MySQL
Topic: MySQL Stored Procedures  

Explanation:  
A stored procedure is a pre‑compiled set of SQL statements stored in the database server.  
It can accept input parameters, return output parameters, and encapsulate complex logic.  
Using procedures reduces client‑side code duplication and improves performance by avoiding repeated parsing.  
Procedures support control‑flow constructs such as IF, LOOP, and CASE, enabling procedural programming within MySQL.  
They are invoked with CALL and can be granted specific execution rights for security.  

Example (creates a procedure that inserts a new employee and returns the generated ID):  

DELIMITER $$  
CREATE PROCEDURE AddEmployee(  
    IN p_name VARCHAR(100),  
    IN p_department_id INT,  
    OUT p_new_id INT)  
BEGIN  
    -- Insert the new employee record  
    INSERT INTO employees (name, department_id, hired_date)  
    VALUES (p_name, p_department_id, NOW());  
  
    -- Retrieve the auto‑generated primary key  
    SET p_new_id = LAST_INSERT_ID();  
END $$  
DELIMITER ;  

-- Call the procedure and capture the output ID  
SET @newEmpId = 0;  
CALL AddEmployee('Alice Johnson', 3, @newEmpId);  
SELECT @newEmpId AS GeneratedEmployeeID;  
*/

/* JavaScript
Topic: Promises and async/await  

Explanation:  
Promises are objects that represent the eventual completion or failure of an asynchronous operation. They have three states—pending, fulfilled, and rejected—and provide .then, .catch, and .finally methods to handle outcomes. The async/await syntax builds on promises, allowing asynchronous code to be written in a synchronous style, which improves readability. An async function automatically returns a promise, and the await keyword pauses execution until the awaited promise settles. Proper error handling with try / catch inside async functions ensures that rejections are caught without breaking the call stack.  

Code example:  

function fetchData(url) {  
    // Return a promise that resolves after a simulated network delay  
    return new Promise((resolve, reject) => {  
        setTimeout(() => {  
            // Simulate a successful response with JSON data  
            const data = { message: "Data loaded from " + url };  
            resolve(data);  
        }, 1000); // 1‑second delay  
    });  
}  

async function loadAndProcess() {  
    try {  
        // Await the promise returned by fetchData; execution pauses here  
        const result = await fetchData("https://api.example.com/resource");  
        // Process the result after it has been retrieved  
        console.log("Success:", result.message);  
    } catch (error) {  
        // Handle any rejection from fetchData or other errors  
        console.error("Error:", error);  
    } finally {  
        // This block runs regardless of success or failure  
        console.log("Operation completed.");  
    }  
}  

// Invoke the async function  
loadAndProcess();
*/

/* AI
Topic: Chain‑of‑Thought Prompt Engineering for Large Language Models  

Explanation:  
Chain‑of‑Thought (CoT) prompting guides a model to generate step‑by‑step reasoning before delivering the final answer, improving accuracy on complex tasks. By explicitly requesting intermediate steps, the model can decompose problems, reducing hallucinations and logical errors. Effective CoT prompts include clear instructions, examples that show the reasoning process, and a delimiter that signals the answer. This technique works across many domains such as math, coding, and commonsense reasoning. When combined with temperature control, CoT can produce deterministic yet thoughtful outputs.

Code example (Python, OpenAI API):

import os
import json
from openai import OpenAI

# Initialize client – assumes OPENAI_API_KEY is set in environment
client = OpenAI()

def chain_of_thought(question: str) -> str:
    """
    Sends a CoT‑styled prompt to the model and returns the final answer.
    The prompt includes an instruction, a few examples, and the user question.
    """
    # Prompt template with instruction and examples
    prompt = (
        "You are a helpful assistant that solves problems by reasoning step by step.\n\n"
        "Example 1:\n"
        "Q: If a train travels 60 miles per hour for 3 hours, how far does it go?\n"
        "A: Step 1: Multiply speed by time.\n"
        "   60 miles/hour * 3 hours = 180 miles.\n"
        "   Final answer: 180 miles.\n\n"
        "Example 2:\n"
        "Q: What is the sum of the first five prime numbers?\n"
        "A: Step 1: List the first five primes: 2, 3, 5, 7, 11.\n"
        "   Step 2: Add them together.\n"
        "   2+3+5+7+11 = 28.\n"
        "   Final answer: 28.\n\n"
        f"Now solve the following problem:\nQ: {question}\nA:"
    )

    # Call the model
    response = client.chat.completions.create(
        model="gpt-4o-mini",
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,          # deterministic reasoning
        max_tokens=300
    )

    # Extract the text portion of the response
    answer = response.choices[0].message.content.strip()
    # The final answer is usually after the phrase "Final answer:"
    if "Final answer:" in answer:
        return answer.split("Final answer:")[1].strip()
    return answer

# Example usage
question = "A rectangle has width 7 cm and height 9 cm. What is its area?"
print("Question:", question)
print("Answer:", chain_of_thought(question))
*/

