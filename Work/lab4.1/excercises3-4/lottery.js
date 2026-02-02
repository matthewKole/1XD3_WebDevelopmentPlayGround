// 1. Create an empty array to be filled dynamically (flexible arrays)
        const ballBin = [];

        // 2. Populate the array with 100 objects using Object Literals
        for (let i = 0; i < 100; i++) {
            // Determine color and points randomly
            const ballColor = Math.random() < 0.5 ? "red" : "white";
            const ballPoints = Math.floor(Math.random() * 101);

            // Create object using literal notation {field: value}
            const ball = {
                color: ballColor,
                points: ballPoints
            };

            ballBin.push(ball);
        }

        // 3. Game State
        let totalScore = 0;
        let playing = true;
        // Using an object as an associative array to track "already drawn" indices
        const history = {}; 

        alert("Game Started! 100 balls created.");

        // 4. Game Loop
        while (playing) {
            let input = prompt("Enter a ball index (0-99) or 'quit':");

            if (input === null || input.toLowerCase() === "quit") {
                playing = false;
                break;
            }

            let index = parseInt(input);

            // Validation using the concepts from the article
            if (isNaN(index) || index < 0 || index > 99) {
                alert("Invalid index! Use 0-99.");
                continue;
            }

            // Using associative array notation to check if index was used
            if (history[index] !== undefined) {
                alert("Index " + index + " is already in use!");
                continue;
            }

            // Draw the ball and access properties using dot notation
            const selectedBall = ballBin[index];
            
            // Show ball details via alert
            alert("Ball " + index + " is " + selectedBall.color + 
                  " and worth " + selectedBall.points + " points.");

            // Mark this index as used in our associative array
            history[index] = true;

            if (selectedBall.color === "red") {
                totalScore -= selectedBall.points;
                alert("Red Ball! Game Over.");
                playing = false;
            } else {
                totalScore += selectedBall.points;
                alert("White Ball! Current Score: " + totalScore);
            }
        }

        alert("Final Score: " + totalScore);