$(function()
{

    function timeChecker()
    {
        setInterval(function()
        {
            var storedTimeStamp = sessionStorage.getItem("lastTimeStamp");  
            timeCompare(storedTimeStamp);
        },5000); //the time is in milliseconds, so 5000 milliseconds = 5 seconds
    }


    function timeCompare(timeString)
    {
        var maxMinutes  = 1;  //Greater than 1 Minute.
        var currentTime = new Date();
        var pastTime    = new Date(timeString);
        var timeDifference    = currentTime - pastTime;
        var minutesPassed     = Math.floor( (timeDifference/60000) ); 
        // 1 minute = 60000 milliseconds 
        // Math.floor () is used to round up the float number into an (positive) interger  

        if( minutesPassed > maxMinutes)
        {
            sessionStorage.removeItem("lastTimeStamp");
            window.location = "./logout.php";
        }
    }

    if(typeof(Storage) !== "undefined") 
    {
        $(document).on('touchstart mousemove', function()
        {
            var timeStamp = new Date();
            sessionStorage.setItem("lastTimeStamp",timeStamp);
        });

        timeChecker();
    }  
});
