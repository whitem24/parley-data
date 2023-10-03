1.- Import Sports Command

    Summary
    This code snippet is a class called 'ImportSports' that extends the 'Command' class. It is responsible for importing sports data from an external source and saving it to the database.

    Example Usage
    php artisan import:sports
    
    Flow
    - The code starts by getting a unique slug for each sport from an Excel file using the 'UniqueSlug' helper class.
    - It retrieves the slugs of the existing sports from the 'Sport' model.
    - It calculates the difference between the unique slugs and the existing slugs to find the new sports that need to be imported.
    - If there are new sports, it iterates over each new slug and performs the following steps:
    -- Prints the slug of the sport being processed.
    -- Constructs the API endpoint for the sport using the slug.
    -- Sends an HTTP GET request to the endpoint to retrieve the sport data.
    -- If the response is not empty, it creates a new 'Sport' model instance with the retrieved data.
    -- Saves the new sport to the database.
    -- Prints a success message if new sports have been imported, or a message indicating that all sports already exist in the database.
    
    Outputs
    Success message if new sports have been imported.
    Message indicating that all sports already exist in the database.

2.- Import Sports Job

    Summary
    The code snippet is a class called ImportSportsJob that implements the ShouldQueue interface. It is a job that can be queued and executed asynchronously. The class includes a constructor and a handle method. The handle method calls the Artisan command import:sports to execute a specific task.

    Flow
    - The ImportSportsJob class is defined and implements the ShouldQueue interface.
    - The class includes a constructor that takes no arguments.
    - The handle method is defined, which is the main method of the job.
    - Inside the handle method, the Artisan command import:sports is called using the Artisan::call() method.

3.- Import Leagues Command

    Summary
    This code snippet is a class called 'ImportLeagues' that is responsible for importing leagues for each sport by retrieving data from an external ESPN API and saving it to the database.

    Example Usage
    php artisan import:leagues
    
    Flow
    - The code retrieves the slug and parent slug of the leagues from an Excel file using the 'SlugWithParent' helper class.
    - It retrieves the slugs of the existing leagues from the database.
    - It filters the slugs that are not already in the database.
    - If there are any new leagues, it iterates over each slug and performs the following steps:
    -- Constructs the endpoint URL for retrieving league data from the ESPN API.
    -- Retrieves the sport associated with the league.
    -- Sends a GET request to the endpoint and retrieves the league data.
    -- Creates a new 'League' model instance and populates its properties with the retrieved data.
    -- Saves the new league to the database.
    -- If there are no new leagues, it outputs a message indicating that all the leagues already exist in the database.
    Outputs
    -- If there are new leagues, it outputs a message indicating that the leagues have been imported successfully.
    -- If there are no new leagues, it outputs a message indicating that all the leagues already exist in the current database.

4.- Import Leagues Job

    Summary
    The code snippet is a class called ImportLeaguesJob that implements the ShouldQueue interface. It represents a job for importing leagues and includes a constructor and a handle method.

    Flow
    - The ImportLeaguesJob class is defined and it implements the ShouldQueue interface.
    - The class uses several traits: Batchable, Dispatchable, InteractsWithQueue, Queueable, and SerializesModels.
    - The class has an empty constructor.
    - The handle method is defined and it calls the Artisan::call method to execute the import:leagues command.

5.- Import Teams Command

    Summary
    This code snippet is a class called ImportTeams that is responsible for importing teams from an external API and storing them in the database.

    Example Usage
    php artisan import:teams
    
    Flow
    - The code starts by fetching all the leagues from the League model and stores them in the $leagues variable.
    - It then iterates over each league and fetches the sport slug from the Sport model.
    - The API endpoint is constructed using the fetched sport slug and league slug.
    - The code makes an HTTP GET request to the API endpoint and stores the response in the $responseLeagues variable.
    - If the response is not empty, it extracts the teams from the response and iterates over each team.
    - For each team, it creates or updates a record in the Team model with the corresponding data.
    - If the team is successfully created or updated, it associates the team with the league.
    - Finally, the code outputs a success message.
    
    Outputs
    - The teams are imported from the external API and stored in the database.
    - A success message is displayed.

6.- Import Teams Job

    Summary
    This code snippet is a class called ImportTeamsJob that implements the ShouldQueue interface. It is responsible for handling the import of teams by calling the import:teams Artisan command.

    Flow
    - The ImportTeamsJob class is defined and it implements the ShouldQueue interface.
    - The handle method is called when the job is processed.
    - Inside the handle method, the import:teams Artisan command is called using the Artisan::call method.


7.- Import Seasons Command:
    Summary
    This code snippet is a Laravel command class that imports seasons for different leagues. It retrieves the leagues from the database, makes HTTP requests to the provided endpoints for each league, and saves the seasons data into the database.

    Example Usage
    php artisan import:seasons
    
    Flow
    - Retrieve all leagues from the database.
    - Iterate over each league.
    - Retrieve the sport associated with the league.
    - Make an HTTP GET request to the league's seasonsRef endpoint.
    - If the response is not empty, retrieve the seasons data.
    - Iterate over each season.
    - Make an HTTP GET request to the season's $ref endpoint.
    - If the response is not empty, retrieve the season data.
    - Create or update a Season model with the retrieved data.
    - Display information about the processed season.
    - Display information about the successfully imported seasons for the league.
    - Display a message when the process is completed.
    
    Outputs
    Information about the start and completion of the process.
    Information about the processed season.
    Information about the successfully imported seasons for each league.

8.- Import Seasons Job

    Summary
    The code snippet is a class called ImportSeasonsJob that implements the ShouldQueue interface. It is responsible for handling the import of seasons by calling the import:seasons Artisan command.

    Flow
    - The ImportSeasonsJob class is defined and it implements the   ShouldQueue interface.
    - The handle method is called when the job is executed.
    - Inside the handle method, the import:seasons Artisan command is called using the Artisan::call method.

9.- Import League command

    Summary
    This code snippet is a class called ImportLeague that extends the Command class in Laravel. It is responsible for importing a league from an API based on the provided sport and league slugs.

    Example Usage
    php artisan import:league football premier-league
    
    Inputs
    sport_slug: The slug of the sport.
    league_slug: The slug of the league.
    
    Flow
    - The command receives the sport and league slugs as arguments.
    - It constructs the API endpoint using the slugs and the configured API endpoints.
    - It sends a GET request to the API to retrieve the league data.
    - If the league data is available, it sends another GET request to retrieve the sport data.
    - It processes and saves the sport data into the database using the Sport model.
    - It checks if the sport was recently created or already exists and displays the appropriate message.
    - It processes and saves the league data into the database using the League model.
    - It checks if the league was recently created or already exists and displays the appropriate message.

    Outputs
    Information messages indicating the progress and status of the import process.

10.- Dispatch Batch

    Summary
    This code snippet is a class called DispatchBatch that extends the Command class in Laravel. It is a console command that dispatches batch jobs for importing sports, leagues, seasons, and teams. It uses the Bus facade to create a batch of jobs and dispatch them. After dispatching the jobs, it calls the queue:work command to process the imports. If any exception occurs during the execution, it catches the exception and displays the error message.

    Example Usage
    php artisan dispatch:imports

    Flow
    - The handle method is called when the dispatch:imports command is executed.
    - It displays an information message "Dispatching jobs...".
    - It creates a batch of jobs using the Bus::batch method, passing in instances of ImportSportsJob, ImportLeaguesJob, ImportSeasonsJob, and ImportTeamsJob.
    - It dispatches the batch of jobs.
    - It displays an information message "Processing imports...".
    - It calls the queue:work --stop-when-empty command using the Artisan::call method to process the imports.
    - It displays an information message "Batch imports have been done successfully."
    - If any exception occurs during the execution, it catches the exception and displays the error message.

11.- Dispatch jobs

    Summary
    This code snippet is a class called DispatchBackgroundJobs that extends the Command class in Laravel. It defines a console command that dispatches multiple jobs in the background for importing sports, leagues, seasons, and teams. After dispatching the jobs, it processes the imports by calling the queue:work command and stops when the queue is empty.

    Example Usage
    php artisan dispatch:imports-jobs

    Flow
    - The handle method is called when the console command is executed.
    - The method dispatches four jobs: ImportSportsJob, ImportLeaguesJob, ImportSeasonsJob, and ImportTeamsJob.
    - It then outputs a message indicating that the jobs are being processed.
    - The queue:work --stop-when-empty command is called using the Artisan facade to process the jobs in the queue.
    Finally, a message is displayed indicating that the job imports have been done successfully.

    