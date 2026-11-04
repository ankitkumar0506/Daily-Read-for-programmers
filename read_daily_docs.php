<?php
// 2026-11-04 07:45:52

/* PHP
Topic: PHP Generators (yield)

Explanation:
- Generators provide a simple way to implement iterators without the overhead of building a full class.
- They allow you to produce values one at a time using the `yield` keyword, preserving function state between calls.
- This is memory‑efficient for large data sets because only one value is kept in memory at a time.
- Generators can be used in `foreach` loops just like arrays or objects implementing Traversable.
- They also support sending data back into the generator with `send()` and handling termination with `return`.

Code example with comments:
<?php
// A generator function that yields numbers from 1 to $limit
function numberRange(int $limit): Generator {
    for ($i = 1; $i <= $limit; $i++) {
        // Yield the current number and pause execution
        yield $i;
    }
}

// Use the generator in a foreach loop
foreach (numberRange(5) as $num) {
    // Each iteration receives the next yielded value
    echo "Number: $num\n";
}

// Demonstrating sending a value back into the generator
function echoGenerator(): Generator {
    $value = yield;            // Wait for a value to be sent
    echo "Received: $value\n"; // Output the received value
}

$gen = echoGenerator();
$gen->rewind();                // Start the generator, moves to first yield
$gen->send('Hello World');     // Sends 'Hello World' into the generator
?>
*/

/* Laravel
Topic: Laravel Custom Validation Rule

Explanation:  
A custom validation rule allows you to encapsulate complex validation logic into a reusable class.  
Implement the Rule contract which requires a passes method to evaluate the value and a message method for the error text.  
Register the rule directly in a form request or inline when defining validation rules.  
Custom rules keep controllers thin and promote single‑responsibility principles.  
They can be used anywhere Laravel’s validator is invoked, including API resources and console commands.

Code example:
<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Uppercase implements Rule
{
    // Determine if the validation rule passes.
    public function passes($attribute, $value)
    {
        // The value must be entirely uppercase.
        return strtoupper($value) === $value;
    }

    // Get the validation error message.
    public function message()
    {
        // :attribute will be replaced with the actual field name.
        return 'The :attribute must be uppercase.';
    }
}

// Usage in a Form Request or controller:
use App\Rules\Uppercase;

$validated = $request->validate([
    'title' => ['required', 'string', new Uppercase], // title must be all caps
    // other fields...
]);

// If validation fails, Laravel automatically returns an error response
// containing the message defined in the Uppercase rule.
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries in MySQL  

Explanation:  
- A CTE is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
- Defined using the WITH clause, it improves readability by separating complex subqueries from the main query.  
- MySQL 8.0+ supports both non‑recursive and recursive CTEs, enabling hierarchical data processing.  
- Recursive CTEs repeatedly execute the CTE definition until a termination condition is met, which is useful for traversing trees such as organizational charts or category hierarchies.  
- They are evaluated once per statement, so they can be referenced multiple times without re‑computing the underlying logic.  

Code example (with inline comments):  

-- Create a simple table to store an employee hierarchy  
CREATE TABLE employees (  
    emp_id INT PRIMARY KEY,  
    emp_name VARCHAR(50) NOT NULL,  
    manager_id INT NULL   -- references emp_id of the manager; NULL for top‑level boss  
);  

-- Insert sample data  
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES  
(1, 'Alice', NULL),   -- CEO  
(2, 'Bob', 1),        -- reports to Alice  
(3, 'Carol', 1),      -- reports to Alice  
(4, 'Dave', 2),       -- reports to Bob  
(5, 'Eve', 2),        -- reports to Bob  
(6, 'Frank', 3);      -- reports to Carol  

-- Recursive CTE to list each employee with their hierarchy level and path from the CEO  
WITH RECURSIVE emp_hierarchy AS (  
    -- Anchor member: start with top‑level employees (no manager)  
    SELECT  
        emp_id,  
        emp_name,  
        manager_id,  
        1 AS level,                     -- level 1 = top of hierarchy  
        CAST(emp_name AS CHAR(200)) AS path   -- initial path contains only the employee name  
    FROM employees  
    WHERE manager_id IS NULL  
  
    UNION ALL  
  
    -- Recursive member: join each employee to its manager's row from the previous iteration  
    SELECT  
        e.emp_id,  
        e.emp_name,  
        e.manager_id,  
        eh.level + 1 AS level,  
        CONCAT(eh.path, ' -> ', e.emp_name) AS path   -- build the path string  
    FROM employees e  
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.emp_id  
)  
SELECT emp_id, emp_name, manager_id, level, path  
FROM emp_hierarchy  
ORDER BY level, emp_id;   -- result shows hierarchy depth and full reporting chain  
*/

/* JavaScript
Topic: JavaScript Closures  

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This allows the inner function to preserve state across multiple invocations without using global variables. Closures are created every time a function is defined, and they capture the lexical environment at that moment. They are fundamental for patterns like data encapsulation, function factories, and partial application. Understanding closures helps you write more modular and memory‑efficient code.

Code example with comments:  
function makeCounter() {  
    let count = 0; // private variable that lives in the outer scope  
    return function() { // inner function forms a closure over count  
        count++; // modify the captured variable  
        return count; // return the updated value  
    };  
}  

const counter = makeCounter(); // create a new closure instance  
console.log(counter()); // 1  
console.log(counter()); // 2  
console.log(counter()); // 3  

// Each call to makeCounter() would produce an independent counter because a new closure is created.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API

Explanation:  
Few‑shot prompting supplies a small set of example interactions within the prompt to guide the model’s behavior without fine‑tuning.  
By formatting the examples as a dialogue, the model can infer the desired pattern and apply it to new inputs.  
This technique is especially useful for tasks like text classification, data extraction, or generating structured responses.  
Choosing clear, representative examples and keeping the total token count within limits are key to success.  
The approach works across models that accept the chat format (gpt‑3.5‑turbo, gpt‑4, etc.).

Python code example (requires the openai package and an API key set in the environment):

import os
import openai

# Load API key from environment variable OPENAI_API_KEY
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(user_input):
    # Define a few‑shot prompt with two labeled examples
    messages = [
        {"role": "system", "content": "You are a helpful assistant that classifies the sentiment of a sentence as Positive, Negative, or Neutral."},
        {"role": "user", "content": "I love the new design of this app!"},
        {"role": "assistant", "content": "Positive"},
        {"role": "user", "content": "The update broke all my shortcuts."},
        {"role": "assistant", "content": "Negative"},
        # New query to classify
        {"role": "user", "content": user_input}
    ]

    # Call the chat completion endpoint
    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=messages,
        temperature=0  # deterministic output
    )
    # Extract the model's reply (the sentiment label)
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
if __name__ == "__main__":
    test_sentence = "The tutorial was okay, not great but useful."
    print(f"Sentence: {test_sentence}")
    print(f"Sentiment: {classify_sentiment(test_sentence)}")
*/

