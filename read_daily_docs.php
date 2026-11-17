<?php
// 2026-11-17 09:33:46

/* PHP
PHP Topic: Generators (yield)

Explanation:
Generators allow you to create iterators without the overhead of building an entire array in memory.  
By using the keyword yield, a function can pause its execution and return a value, then resume later.  
This is especially useful when processing large data sets, streams, or database results.  
Generators provide a simple syntax for lazy iteration, improving performance and reducing memory usage.  
They can be combined with foreach loops just like regular arrays or objects implementing Traversable.  

Code example with comments:

function getNumbers($max) {
    // Loop from 1 to the maximum value
    for ($i = 1; $i <= $max; $i++) {
        // Yield the current number and pause execution
        yield $i;
        // Execution resumes here on the next iteration
    }
}

// Create a generator that will produce numbers up to 5
$numbers = getNumbers(5);

// Iterate over the generator with foreach
foreach ($numbers as $num) {
    // Output each number
    echo "Number: $num\n";
}

// The generator can be rewound by creating a new instance
$moreNumbers = getNumbers(3);
while ($moreNumbers->valid()) {
    echo "Next: " . $moreNumbers->current() . "\n";
    $moreNumbers->next();
}
*/

/* Laravel
Laravel Topic: Eloquent Mutators and Accessors  

Explanation:  
Eloquent mutators allow you to alter attribute values before they are saved to the database, while accessors let you format attribute values when they are retrieved. They are defined as methods on your model using the setAttributeNameAttribute and getAttributeNameAttribute naming conventions. This feature is useful for tasks such as hashing passwords, formatting dates, or appending units to numeric values. Mutators and accessors keep transformation logic encapsulated within the model, promoting clean controller code. They work automatically whenever you assign or retrieve the attribute on a model instance.

Code example (User model with password mutator and full_name accessor):

<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // Mutator: automatically hash password when it is set
    public function setPasswordAttribute($value)
    {
        // Only hash if the value is not already hashed
        $this->attributes['password'] = \Hash::needsRehash($value) ? \Hash::make($value) : $value;
    }

    // Accessor: combine first_name and last_name into a full_name attribute
    public function getFullNameAttribute()
    {
        // Trim to avoid extra spaces if one part is missing
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // Example usage in a controller or tinker:
    // $user = new User;
    // $user->first_name = 'Jane';
    // $user->last_name = 'Doe';
    // $user->password = 'secret123';   // Mutator hashes this value
    // $user->save();
    // echo $user->full_name;           // Accessor returns "Jane Doe"
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) in MySQL

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve query readability by allowing you to break complex logic into named subqueries.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.  
Recursive CTEs enable hierarchical queries such as traversing organizational charts or tree structures.  
CTEs exist only for the duration of the statement; they are not stored in the database schema.

Code example with comments:  

CREATE TABLE employees (  
    emp_id INT PRIMARY KEY,  
    name VARCHAR(50),  
    manager_id INT NULL   -- References emp_id of the employee's manager  
);  

INSERT INTO employees (emp_id, name, manager_id) VALUES  
(1, 'Alice', NULL),  
(2, 'Bob', 1),  
(3, 'Carol', 1),  
(4, 'Dave', 2),  
(5, 'Eve', 2);  

-- Recursive CTE to list each employee with their reporting hierarchy depth  
WITH RECURSIVE emp_hierarchy AS (  
    -- Anchor member: top‑level managers (no manager) start at depth 1  
    SELECT emp_id, name, manager_id, 1 AS depth  
    FROM employees  
    WHERE manager_id IS NULL  

    UNION ALL  

    -- Recursive member: join employees to their managers and increment depth  
    SELECT e.emp_id, e.name, e.manager_id, eh.depth + 1  
    FROM employees e  
    JOIN emp_hierarchy eh ON e.manager_id = eh.emp_id  
)  
SELECT emp_id, name, manager_id, depth  
FROM emp_hierarchy  
ORDER BY depth, manager_id;   -- Result shows each employee and how many levels down they are from the top manager.
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures enable data privacy by allowing inner functions to reference variables defined in an outer function that would otherwise be inaccessible.  
They are created every time a function is defined, capturing the surrounding environment at that moment.  
Common use‑cases include function factories, module patterns, and maintaining state across asynchronous callbacks.  
Understanding closures helps avoid common pitfalls such as unintentionally sharing mutable variables between invocations.

Code Example (with comments):
function makeCounter(start) {
    // `count` is private to the closure created by makeCounter
    let count = start; 

    // The returned function forms a closure over `count`
    return function increment(step = 1) {
        // Modify the captured `count` variable
        count += step;       
        // Return the current value
        return count;       
    };
}

// Create a new counter starting at 10
const counterA = makeCounter(10);
console.log(counterA());      // 11 (default step 1)
console.log(counterA(5));     // 16 (adds 5)
console.log(counterA());      // 17

// Create a separate counter, demonstrating independent closures
const counterB = makeCounter(100);
console.log(counterB(10));    // 110
console.log(counterB());      // 111   (does not affect counterA)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s ChatCompletion API  

Explanation:  
Few‑shot prompting supplies a small set of example interactions within a single request to guide the model’s behavior. By placing well‑chosen input‑output pairs before the actual user query, you can shape the style, format, or domain knowledge the model applies. This technique works especially well with GPT‑4‑Turbo, where each example counts against the token limit but yields more deterministic results. It is useful for tasks like generating code snippets, extracting structured data, or maintaining a consistent tone across responses. The approach is lightweight—no fine‑tuning required—and can be adjusted dynamically at runtime.

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from the environment  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt with two examples and the new user request  
messages = [  
    {"role": "system", "content": "You are an expert Python programmer who writes clear, commented code."},  
    {"role": "user", "content": "Write a function that returns the factorial of a number."},  
    {"role": "assistant", "content": """def factorial(n):\n    \"\"\"Return the factorial of n using recursion.\"\"\"\n    if n == 0:\n        return 1\n    return n * factorial(n - 1)  # Recursive call"""},  
    {"role": "user", "content": "Create a function that checks if a string is a palindrome, ignoring case and spaces."}  
]  

# Send the request to the ChatCompletion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",   # choose a model that supports chat completions  
    messages=messages,  
    temperature=0.2,       # low temperature for more deterministic output  
)  

# Extract and print the assistant’s answer  
assistant_reply = response["choices"][0]["message"]["content"]  
print(assistant_reply)   # The generated palindrome‑checking function with comments  
*/

