<?php
// 2026-11-27 09:58:22

/* PHP
PHP PDO Prepared Statements

Prepared statements in PDO allow you to safely execute SQL queries with user‑supplied data. The query is compiled once by the database, and placeholders are used for the actual values, which prevents SQL injection. You bind parameters to the placeholders, specifying the data type for each. This method also improves performance when the same statement is run multiple times with different values. PDO supports both named and positional placeholders, giving flexibility in how you write your queries.

<?php
// Create a new PDO instance (replace DSN, username, and password with your own)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass');
// Enable exceptions for error handling
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Define an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)";

// Prepare the statement (the SQL is sent to the server for parsing)
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username',  $userName, PDO::PARAM_STR);
$stmt->bindValue(':email',     $userEmail, PDO::PARAM_STR);
$stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

// Execute the prepared statement
$stmt->execute();

// Check how many rows were inserted
echo $stmt->rowCount() . " row(s) inserted.";
?>
*/

/* Laravel
Topic: Laravel Service Container & Dependency Injection  

Explanation:  
The Service Container is Laravel’s powerful tool for managing class dependencies and performing dependency injection. It resolves classes automatically, injecting required dependencies into constructors or methods. By binding an interface to a concrete implementation, you can swap implementations without changing the consuming code. This promotes loose coupling and makes testing easier through mocking. The container is accessed via the app() helper or through type‑hinting in controllers, jobs, listeners, etc.  

Code example (plain text, with comments):  

// Define an interface for a payment gateway
interface PaymentGateway {
    public function charge($amount);
}

// Concrete implementation using Stripe
class StripeGateway implements PaymentGateway {
    public function charge($amount) {
        // Here you would call Stripe's API to process the payment
        // For demonstration we just return a success message
        return "Charged \$$amount via Stripe.";
    }
}

// Bind the interface to the implementation in a service provider
class AppServiceProvider extends ServiceProvider {
    public function register() {
        // When PaymentGateway is requested, resolve it to StripeGateway
        $this->app->bind(PaymentGateway::class, StripeGateway::class);
    }
}

// Controller that receives the dependency via constructor injection
class OrderController extends Controller {
    protected $paymentGateway;

    // Laravel automatically injects the bound implementation
    public function __construct(PaymentGateway $paymentGateway) {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request) {
        $amount = $request->input('amount');
        // Use the injected service to charge the amount
        $result = $this->paymentGateway->charge($amount);
        return response()->json(['message' => $result]);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It improves readability by allowing you to break complex queries into logical building blocks. CTEs are defined using the WITH clause and can be recursive, enabling hierarchical data traversal such as organizational charts or tree structures. Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself. They are evaluated iteratively until the result set stops growing, making them powerful for depth‑first or breadth‑first traversals.

Code Example:
WITH RECURSIVE OrgChart AS (  
    -- Anchor member: start with the top‑level manager (e.g., employee_id = 1)  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL AND employee_id = 1  

    UNION ALL  

    -- Recursive member: find direct reports of the current level  
    SELECT e.employee_id, e.manager_id, e.employee_name, oc.level + 1  
    FROM employees e  
    INNER JOIN OrgChart oc ON e.manager_id = oc.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM OrgChart  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: Async Generators and Asynchronous Iteration

Explanation:  
Async generators combine the power of generators with the ability to pause for asynchronous operations. They are declared with the “async function*” syntax and yield promises that are awaited by the consumer. This pattern is ideal for processing streams of data that arrive over time, such as reading lines from a file or handling paginated API responses. The consumer uses a “for await…of” loop, which automatically waits for each yielded value before proceeding. Async generators keep the code readable and avoid deep nesting of callbacks or promise chains.

Code example with comments:  
async function* fetchPages(url) {  
    // Start with the first page URL  
    let nextUrl = url;  
    while (nextUrl) {  
        // Wait for the HTTP request to complete  
        const response = await fetch(nextUrl);  
        const data = await response.json();  
        // Yield the items from the current page to the caller  
        yield data.items;  
        // Determine the URL of the next page, if any  
        nextUrl = data.nextPageUrl;  
    }  
}  

// Consumer of the async generator  
(async () => {  
    // Loop over each batch of items as they become available  
    for await (const items of fetchPages('https://api.example.com/data')) {  
        // Process each batch synchronously inside the loop  
        console.log('Received batch of', items.length, 'items');  
        // Example: handle each item individually  
        items.forEach(item => console.log(item.id, item.name));  
    }  
    console.log('All pages have been processed');  
})();
*/

/* AI
Topic: Simple Feedforward Neural Network with PyTorch  

Explanation:  
A feedforward neural network (also called a multilayer perceptron) consists of an input layer, one or more hidden layers, and an output layer. Each layer applies a linear transformation followed by a non‑linear activation, allowing the model to learn complex mappings from inputs to targets. PyTorch provides a clean, modular API to define layers, loss functions, and optimizers, making experimentation fast. In this example we build a network to classify the Iris dataset, a classic small‑scale classification problem. The code demonstrates dataset loading, model definition, training loop, and evaluation in just a few lines.

Code example (Python with PyTorch):  

import torch
import torch.nn as nn
import torch.optim as optim
from torch.utils.data import TensorDataset, DataLoader
from sklearn.datasets import load_iris
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler

# Load and preprocess the Iris data
iris = load_iris()
X = iris['data'].astype('float32')
y = iris['target'].astype('int64')
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Standardize features for better training stability
scaler = StandardScaler()
X_train = scaler.fit_transform(X_train)
X_test = scaler.transform(X_test)

# Convert to PyTorch tensors and create DataLoaders
train_dataset = TensorDataset(torch.tensor(X_train), torch.tensor(y_train))
test_dataset = TensorDataset(torch.tensor(X_test), torch.tensor(y_test))
train_loader = DataLoader(train_dataset, batch_size=16, shuffle=True)
test_loader = DataLoader(test_dataset, batch_size=16, shuffle=False)

# Define a simple feedforward network
class SimpleMLP(nn.Module):
    def __init__(self, input_dim, hidden_dim, output_dim):
        super(SimpleMLP, self).__init__()
        self.fc1 = nn.Linear(input_dim, hidden_dim)   # First linear layer
        self.relu = nn.ReLU()                         # Non‑linear activation
        self.fc2 = nn.Linear(hidden_dim, output_dim) # Output layer

    def forward(self, x):
        out = self.fc1(x)
        out = self.relu(out)
        out = self.fc2(out)
        return out

model = SimpleMLP(input_dim=4, hidden_dim=16, output_dim=3)

# Loss function and optimizer
criterion = nn.CrossEntropyLoss()
optimizer = optim.Adam(model.parameters(), lr=0.01)

# Training loop
num_epochs = 50
for epoch in range(num_epochs):
    model.train()
    for batch_X, batch_y in train_loader:
        optimizer.zero_grad()               # Clear gradients
        outputs = model(batch_X)            # Forward pass
        loss = criterion(outputs, batch_y)  # Compute loss
        loss.backward()                     # Backward pass
        optimizer.step()                    # Update weights
    # Optional: print loss every 10 epochs
    if (epoch+1) % 10 == 0:
        print(f'Epoch [{epoch+1}/{num_epochs}], Loss: {loss.item():.4f}')

# Evaluation on test set
model.eval()
correct = 0
total = 0
with torch.no_grad():
    for batch_X, batch_y in test_loader:
        outputs = model(batch_X)
        _, predicted = torch.max(outputs, 1)
        total += batch_y.size(0)
        correct += (predicted == batch_y).sum().item()
accuracy = 100 * correct / total
print(f'Test Accuracy: {accuracy:.2f}%')
*/

