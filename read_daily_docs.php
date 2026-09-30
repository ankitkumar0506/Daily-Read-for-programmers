<?php
// 2026-09-30 07:05:29

/* PHP
PHP Topic: Traits

Explanation:
Traits are a mechanism for code reuse in single inheritance languages like PHP. They allow you to group methods that can be included in multiple classes, avoiding duplication. A trait can contain properties, methods, and even abstract method declarations. Classes use the `use` keyword to incorporate a trait, and they can resolve method name conflicts with the `insteadof` and `as` operators. Traits are especially useful for sharing functionality across unrelated class hierarchies.

Code Example:
<?php
// Define a trait with reusable methods
trait Logger {
    // Log a message with a timestamp
    public function log(string $message) {
        echo "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
    }

    // Helper method to format messages
    protected function formatMessage(string $level, string $msg) {
        return strtoupper($level) . ": " . $msg;
    }
}

// First class using the Logger trait
class User {
    use Logger; // include the Logger trait

    public function create(string $username) {
        // Perform user creation logic here...
        $this->log($this->formatMessage('info', "User '{$username}' created."));
    }
}

// Second class also using the Logger trait
class Order {
    use Logger; // include the same Logger trait

    public function place(int $orderId) {
        // Perform order placement logic here...
        $this->log($this->formatMessage('success', "Order #{$orderId} placed successfully."));
    }
}

// Demonstration
$user = new User();
$user->create('alice');

$order = new Order();
$order->place(12345);
?>
*/

/* Laravel
Topic: Laravel Service Container & Dependency Injection

Explanation:  
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection automatically. It resolves objects by inspecting constructor type hints and injecting the required instances, which promotes loose coupling and easier testing. You can bind abstractions to concrete implementations, configure singleton instances, and even use contextual bindings for specific scenarios. The container is accessed via the app() helper or the resolve() method, making it simple to retrieve resolved objects anywhere in your application. Understanding the container is essential for building maintainable, testable Laravel services and repositories.

Code example (PHP):

<?php
// Define an interface for a payment gateway
interface PaymentGatewayContract {
    public function charge(float $amount);
}

// Concrete implementation for Stripe
class StripeGateway implements PaymentGatewayContract {
    public function charge(float $amount) {
        // Simulated charge logic
        return "Charged \${$amount} using Stripe.";
    }
}

// Concrete implementation for PayPal
class PayPalGateway implements PaymentGatewayContract {
    public function charge(float $amount) {
        // Simulated charge logic
        return "Charged \${$amount} using PayPal.";
    }
}

// Service provider where bindings are registered
class AppServiceProvider extends Illuminate\Support\ServiceProvider {
    public function register() {
        // Bind the interface to a concrete class (default to Stripe)
        $this->app->bind(PaymentGatewayContract::class, StripeGateway::class);

        // Example of a singleton binding
        $this->app->singleton('logger', function ($app) {
            return new Monolog\Logger('app');
        });

        // Contextual binding: when OrderProcessor needs PaymentGatewayContract, give PayPalGateway
        $this->app->when(OrderProcessor::class)
                  ->needs(PaymentGatewayContract::class)
                  ->give(PayPalGateway::class);
    }
}

// A class that depends on the payment gateway via constructor injection
class OrderProcessor {
    protected $gateway;

    // Laravel will automatically inject the appropriate implementation
    public function __construct(PaymentGatewayContract $gateway) {
        $this->gateway = $gateway;
    }

    public function process(float $amount) {
        // Use the injected gateway to charge the amount
        return $this->gateway->charge($amount);
    }
}

// Resolving the OrderProcessor from the container
$orderProcessor = app(OrderProcessor::class);
echo $orderProcessor->process(99.99); // Outputs: Charged $99.99 using PayPal.

// Directly resolving a bound singleton
$logger = resolve('logger');
$logger->info('Order processed successfully.');
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
They are defined using the WITH clause and can be recursive, enabling hierarchical data traversal such as organizational charts or tree structures.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.

Code example (recursive CTE to list an employee hierarchy):
/* Sample table */
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL   -- references emp_id of the manager
);

/* Insert sample data */
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),   -- top‑level manager
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'David', 2),
(5, 'Eve', 2);

/* Recursive CTE to retrieve the hierarchy starting from Alice (emp_id = 1) */
WITH RECURSIVE emp_hierarchy AS (
    -- Anchor member: start with the top manager
    SELECT emp_id, emp_name, manager_id, 0 AS level
    FROM employees
    WHERE emp_id = 1

    UNION ALL

    -- Recursive member: find direct reports of the previous level
    SELECT e.emp_id, e.emp_name, e.manager_id, eh.level + 1
    FROM employees e
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.emp_id
)
SELECT emp_id,
       emp_name,
       manager_id,
       REPEAT('  ', level) || emp_name AS hierarchy_path
FROM emp_hierarchy
ORDER BY level, emp_id;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures allow inner functions to read variables from outer functions after the outer function has finished executing.  
They are useful for data encapsulation, creating private variables, and implementing function factories.  
Understanding closures helps avoid common pitfalls with variable sharing in asynchronous code and loops.  
Because the closed‑over variables live on the heap, they remain in memory as long as any reference to the closure exists.  

Code example with comments:  
function createCounter(initialValue) {          // outer function that defines a private variable  
    let count = initialValue;                  // this variable is captured by the inner function  

    return function increment(step = 1) {     // inner function forms a closure over 'count'  
        count += step;                         // modifies the private variable  
        console.log('Current count:', count); // demonstrates that state is preserved  
        return count;                          // returns the updated count  
    };                                          // end of inner function  

}                                               // end of outer function  

// Usage:  
const counter = createCounter(10);  // 'counter' now holds the closure with its own 'count'  

counter();        // Current count: 11  
counter(5);       // Current count: 16  
counter();        // Current count: 17  

// Each call to createCounter produces an independent closure with its own private 'count' variable.
*/

/* AI
Topic: Parameter-Efficient Fine‑Tuning of a Language Model with LoRA (Low‑Rank Adaptation)

Explanation:  
LoRA adds trainable low‑rank matrices to the weight tensors of a frozen pretrained model, allowing efficient adaptation with far fewer parameters. It keeps the original model unchanged, so inference remains fast and memory‑efficient. This approach is especially useful for developers who need custom behavior without the cost of full fine‑tuning. Using the Hugging Face Transformers and PEFT libraries, you can apply LoRA to models like Llama‑2 or Mistral in just a few lines of code. The method works for tasks such as classification, summarization, or instruction following, and the resulting adapter can be shared or re‑loaded independently of the base model.

Code example (Python):

import torch
from transformers import AutoModelForCausalLM, AutoTokenizer
from peft import LoraConfig, get_peft_model, prepare_model_for_int8_training

# Load a pretrained causal language model and its tokenizer
model_name = "meta-llama/Llama-2-7b-hf"
tokenizer = AutoTokenizer.from_pretrained(model_name)
model = AutoModelForCausalLM.from_pretrained(model_name, device_map="auto", torch_dtype=torch.float16)

# Optional: convert model to 8‑bit for lower GPU memory usage
model = prepare_model_for_int8_training(model)

# Define LoRA configuration: rank r=8, alpha scaling, target modules to adapt
lora_cfg = LoraConfig(
    r=8,
    lora_alpha=16,
    target_modules=["q_proj", "k_proj", "v_proj", "o_proj"],  # attention projections
    lora_dropout=0.05,
    bias="none",
    task_type="CAUSAL_LM"
)

# Wrap the base model with LoRA adapters
model = get_peft_model(model, lora_cfg)

# Example training loop (single batch for illustration)
inputs = tokenizer("Explain quantum entanglement in simple terms.", return_tensors="pt").to(model.device)
labels = inputs.input_ids.clone()  # language modeling objective
outputs = model(input_ids=inputs.input_ids, labels=labels)
loss = outputs.loss
loss.backward()
torch.nn.utils.clip_grad_norm_(model.parameters(), max_norm=1.0)
# optimizer step (optimizer defined elsewhere)
# optimizer.step()
# optimizer.zero_grad()

# Save only the LoRA adapter weights
model.save_pretrained("lora_llama2_adapter")
tokenizer.save_pretrained("lora_llama2_adapter")

# Inference with the fine‑tuned adapter
model.eval()
prompt = "What are the health benefits of regular exercise?"
input_ids = tokenizer(prompt, return_tensors="pt").to(model.device).input_ids
generated_ids = model.generate(input_ids, max_new_tokens=100, do_sample=True, temperature=0.7)
print(tokenizer.decode(generated_ids[0], skip_special_tokens=True))
*/

