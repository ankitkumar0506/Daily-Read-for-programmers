<?php
// 2026-09-01 06:34:26

/* PHP
Topic: PHP Generators (Iterators with yield)

Explanation:  
Generators allow functions to produce values one at a time while maintaining their execution state.  
Instead of building an entire array in memory, a generator yields each value when requested.  
They are especially useful for processing large data sets, streaming files, or infinite sequences.  
The `yield` keyword pauses the function, returns a value, and resumes from the same point on the next iteration.  
Using generators can dramatically reduce memory consumption and improve performance in PHP scripts.

Code example with comments:  
function getNumbers($max) {  
    for ($i = 1; $i <= $max; $i++) {  
        // Yield returns the current number without creating an array  
        yield $i;  
    }  
}  

foreach (getNumbers(5) as $number) {  
    // Each number is generated on demand and processed here  
    echo $number . PHP_EOL;  
}
*/

/* Laravel
Topic: Laravel Middleware

Explanation:  
Middleware in Laravel acts as a filtering layer that sits between the incoming HTTP request and the application’s core logic. It can inspect, modify, or reject a request before it reaches the controller, and can also manipulate the response after the controller processes it. Common uses include authentication, logging, CORS handling, and request throttling. Middleware is registered globally, assigned to route groups, or attached to individual routes. By creating custom middleware you can encapsulate any reusable request handling logic across your application.

Code example (Custom middleware that logs request execution time):

<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestTime
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request  The current HTTP request instance.
     * @param  Closure  $next     The next middleware / controller to call.
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Capture the start time in microseconds.
        $start = microtime(true);

        // Pass the request deeper into the stack and get the response.
        $response = $next($request);

        // Calculate the elapsed time.
        $duration = microtime(true) - $start;

        // Log the request path and duration.
        Log::info('Request [' . $request->method() . '] ' . $request->fullUrl() .
                  ' completed in ' . round($duration * 1000, 2) . ' ms');

        // Return the original response back to the client.
        return $response;
    }
}
?> 

To activate this middleware, add its class name to the $routeMiddleware array in app/Http/Kernel.php and attach it to routes or groups as needed.
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that can be referenced within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by isolating complex subqueries.  
When a CTE references itself, it becomes a recursive CTE, useful for hierarchical or graph‑traversal queries.  
Recursive CTEs require an anchor member (the base case) and a recursive member that repeatedly references the CTE.  
The recursion stops when the recursive member returns no rows, preventing infinite loops.

Code example:
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: start with the top‑level manager (e.g., manager_id = 1)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL      -- top of the hierarchy

    UNION ALL

    -- Recursive member: find direct reports of the previous level
    SELECT 
        e.employee_id,
        e.manager_id,
        e.employee_name,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN employee_hierarchy eh
        ON e.manager_id = eh.employee_id
)
SELECT 
    employee_id,
    manager_id,
    employee_name,
    level
FROM employee_hierarchy
ORDER BY level, employee_id;
*/

/* JavaScript
Topic: Closures in JavaScript  

A closure is a function that retains access to its lexical scope even after the outer function has finished executing.  
It allows the inner function to read and modify variables defined in the outer function.  
Closures are created each time a function is defined, not only when it is invoked.  
They are useful for data encapsulation, creating private variables, and building function factories.  
Understanding closures helps avoid pitfalls such as unintentionally sharing state between calls.  
Closures also enable patterns like memoization and currying.  

// Example of a closure that creates a private counter
function createCounter(initialValue) {          // outer function defines a private variable
    let count = initialValue;                  // this variable is captured by the inner function
    return function increment(step) {          // the inner function forms a closure over count
        count += step;                         // modifies the private variable
        return count;                          // returns the updated count
    };
}

const counterA = createCounter(0);              // counterA has its own independent closure
console.log(counterA(1));   // 1
console.log(counterA(5));   // 6

const counterB = createCounter(10);             // counterB has a separate closure
console.log(counterB(2));   // 12
console.log(counterA(3));   // 9   (counterA’s private count continues from 6)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets you guide a large language model by supplying a handful of example input–output pairs directly in the prompt. The model infers the desired pattern and applies it to new queries, often achieving higher quality results than a single instruction. This technique is especially useful when you have a clear but narrow task, such as converting natural language to code snippets or extracting structured data. By carefully formatting examples and using the system message to set context, you can reduce hallucinations and improve consistency. The approach works with the Chat Completion endpoint, which supports role‑based messages (system, user, assistant).  

Code example (Python, using the official openai library):  

import os  
import openai  

# Load your API key from an environment variable for safety  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def generate_sql(query: str) -> str:  
    # Define a short system prompt that establishes the assistant’s role  
    system_msg = {  
        "role": "system",  
        "content": "You are an assistant that translates English questions about a sales database into PostgreSQL queries. Return only the SQL statement, no explanation."  
    }  

    # Provide a few example pairs (few‑shot) in the user‑assistant format  
    examples = [  
        {  
            "role": "user",  
            "content": "Show the total revenue per month for the last year."  
        },  
        {  
            "role": "assistant",  
            "content": "SELECT DATE_TRUNC('month', order_date) AS month, SUM(amount) AS total_revenue FROM sales WHERE order_date >= DATE_TRUNC('year', CURRENT_DATE) - INTERVAL '1 year' GROUP BY month ORDER BY month;"  
        },  
        {  
            "role": "user",  
            "content": "List the top 5 customers by total purchase amount."  
        },  
        {  
            "role": "assistant",  
            "content": "SELECT customer_id, SUM(amount) AS total_spent FROM sales GROUP BY customer_id ORDER BY total_spent DESC LIMIT 5;"  
        },  
        # The new user query we want to translate  
        {  
            "role": "user",  
            "content": query  
        }  
    ]  

    # Call the chat completion endpoint with the assembled messages  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",          # choose a suitable model for code generation  
        messages=[system_msg] + examples,  
        temperature=0.0               # deterministic output for code  
    )  

    # Extract the assistant’s reply (the generated SQL)  
    sql = response.choices[0].message.content.strip()  
    return sql  

# Example usage  
if __name__ == "__main__":  
    user_question = "How many orders were placed in each state last quarter?"  
    sql_query = generate_sql(user_question)  
    print("Generated SQL:")  
    print(sql_query)  
*/

