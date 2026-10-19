<?php
// 2026-10-19 06:31:55

/* PHP
Topic: Anonymous Functions and Closures in PHP

Explanation:
Anonymous functions, also called closures, allow you to create functions without a name and assign them to variables.  
They can capture variables from the surrounding scope, making them useful for callbacks and functional programming patterns.  
In PHP, the use keyword is required to import external variables into the closure’s scope.  
Closures can be passed around, stored in arrays, or returned from other functions.  
They enable more concise and flexible code, especially when working with array functions like array_map or usort.

Code example:
<?php
// Define an array of numbers
$numbers = [1, 2, 3, 4, 5];

// Create an anonymous function that multiplies each element by a factor
$factor = 3;
$multiply = function ($value) use ($factor) {
    // $factor is captured from the parent scope via the use clause
    return $value * $factor;
};

// Apply the closure to each element using array_map
$doubled = array_map($multiply, $numbers);

// Output the resulting array
print_r($doubled); // Expected: Array ( [0] => 3 [1] => 6 [2] => 9 [3] => 12 [4] => 15 )
?>
*/

/* Laravel
Topic: Form Request Validation in Laravel

Explanation:  
Form Request Validation allows you to encapsulate validation logic in a dedicated class, keeping controllers clean and reusable.  
You generate a request class with artisan, define authorization rules, and specify validation rules in the rules() method.  
When the request class is type‑hinted in a controller method, Laravel automatically validates the incoming data before the method runs.  
If validation fails, Laravel redirects back with error messages; on success, you receive a validated data array.  
This approach promotes single responsibility, testability, and centralized error handling for form inputs.

Code example (app/Http/Requests/StorePostRequest.php):

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Determines if the user is authorized to make this request
    public function authorize()
    {
        // Return true to allow all users, or implement custom logic
        return true;
    }

    // Validation rules applied to the incoming request data
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'tags'    => 'array',
            'tags.*'  => 'integer|exists:tags,id',
        ];
    }

    // Custom messages (optional)
    public function messages()
    {
        return [
            'title.required' => 'A title is required for the post.',
            'body.required'  => 'Please provide the content of the post.',
        ];
    }
}

Controller usage (app/Http/Controllers/PostController.php):

<?php
namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    // Store a new blog post
    public function store(StorePostRequest $request)
    {
        // At this point the request data is already validated
        $validated = $request->validated();

        // Create the post using the validated data
        $post = Post::create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
        ]);

        // Attach tags if any were provided
        if (!empty($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        // Return a response or redirect
        return redirect()->route('posts.show', $post);
    }
}
*/

/* MySQL
MySQL Topic: Common Table Expressions (CTEs) and Recursive Queries  

Explanation:  
- A CTE is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
- It is defined using the WITH clause and improves readability by allowing you to break complex queries into logical building blocks.  
- MySQL supports both non‑recursive (single‑query) and recursive CTEs, the latter enabling hierarchical data traversal such as organization charts or tree structures.  
- Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself, combined with UNION [ALL] to iterate until a termination condition is met.  
- Use of CTEs can also help with query optimization, as the optimizer may materialize the CTE once and reuse it, reducing repeated calculations.  

Code Example (recursive CTE to list an employee hierarchy):

-- Sample table definition
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL,            -- references emp_id of the manager
    FOREIGN KEY (manager_id) REFERENCES employees(emp_id)
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),      -- top‑level manager
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'David', 2),
(5, 'Eve', 2),
(6, 'Frank', 3);

-- Recursive CTE to retrieve the full reporting chain for a given employee (e.g., employee 4)
WITH RECURSIVE reporting_chain AS (
    -- Anchor member: start with the target employee
    SELECT
        emp_id,
        emp_name,
        manager_id,
        0 AS level                     -- level 0 = the employee themselves
    FROM employees
    WHERE emp_id = 4

    UNION ALL

    -- Recursive member: join the CTE to its manager
    SELECT
        e.emp_id,
        e.emp_name,
        e.manager_id,
        rc.level + 1 AS level
    FROM employees e
    INNER JOIN reporting_chain rc ON e.emp_id = rc.manager_id
)
SELECT
    emp_id,
    emp_name,
    manager_id,
    level
FROM reporting_chain
ORDER BY level DESC;   -- highest level = top manager

-- Result:
-- emp_id | emp_name | manager_id | level
-- --------------------------------------
-- 1      | Alice    | NULL       | 2
-- 2      | Bob      | 1          | 1
-- 4      | David    | 2          | 0

-- The query walks up the hierarchy from David (emp_id 4) to his manager Bob, then to top‑level manager Alice.  
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
Closures allow private data encapsulation, enabling you to hide implementation details from the outside world.  
They are created every time a function is defined, capturing the current lexical environment.  
Common uses include function factories, module patterns, and maintaining state between calls without polluting the global scope.  
Understanding closures is essential for effective asynchronous programming and managing callbacks.

Code example with comments:  
function makeCounter(initialValue) {  
    let count = initialValue;                // private variable, not accessible from outside  

    return function() {                     // the inner function forms a closure over 'count'  
        count += 1;                          // it can read and modify 'count' each call  
        return count;                        // return the updated count  
    };                                       // the returned function retains reference to 'count'  

}                                          // end of makeCounter  

const counter = makeCounter(10);            // create a new counter starting at 10  

console.log(counter()); // 11                // first call increments to 11  
console.log(counter()); // 12                // second call increments to 12  
console.log(counter()); // 13                // third call increments to 13  

// Even though makeCounter has finished execution, the inner function still accesses 'count' via the closure.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example inputs and desired outputs directly in the prompt, teaching it the pattern to follow. This technique works well for tasks like text classification, data extraction, or style transfer without fine‑tuning. By structuring examples as a conversation (system, user, assistant messages), you can guide the model’s behavior while keeping token usage low. The approach is flexible: you can swap examples, adjust temperature, or add delimiters to improve consistency. It is especially useful for programmers who need quick, adaptable AI helpers in scripts or notebooks.  

Code example (Python, using the openai package):  

import os  
import openai  

# Set your API key – you can also configure it via the OPENAI_API_KEY environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text: str) -> str:  
    # Define a few‑shot prompt with two labeled examples and a new query  
    messages = [  
        {"role": "system", "content": "You are a helpful assistant that classifies sentiment as Positive, Negative, or Neutral."},  
        {"role": "user", "content": "I love the new features in the app! 😊"},  
        {"role": "assistant", "content": "Positive"},  
        {"role": "user", "content": "The update crashed my phone, very frustrating."},  
        {"role": "assistant", "content": "Negative"},  
        {"role": "user", "content": text}  # The text we want classified  
    ]  

    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",        # Choose a lightweight model for cost‑effectiveness  
        messages=messages,  
        temperature=0.0,            # Deterministic output for classification tasks  
        max_tokens=10               # We only need a short label back  
    )  

    # Extract the assistant’s reply and strip whitespace  
    sentiment = response.choices[0].message.content.strip()  
    return sentiment  

# Example usage  
if __name__ == "__main__":  
    test_text = "The tutorial was okay, nothing spectacular."  
    print(f"Sentiment: {classify_sentiment(test_text)}")  
*/

