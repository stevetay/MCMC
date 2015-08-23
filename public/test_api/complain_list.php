<?php 

//parameter: user id (if no user id, then show public list)

echo '
{
    "result": [
        {
            "id": 1,
            "complained_by": {
                "id": 1,
                "name": "Roslan"
            },
            "location": {
                "long": 101.655381,
                "lat": 2.930081,
                "name": "Cyberjaya"
            },
            "title": "PAGAR ROSAK",
            "follower_count": 10,
            "created_on": 1439721796,
            "status": "solved"
        },
        {
            "id": 2,
            "complained_by": {
                "id": 8,
                "name": "Endy"
            },
            "location": {
                "long": 101.655381,
                "lat": 2.930081,
                "name": "Cyberjaya"
            },
            "title": "TRAFFIC LIGHT OUT OF ORDER",
            "follower_count": 100,
            "created_on": 1439721796,
            "status": "scheduled"
        }
    ]
}
';
?>