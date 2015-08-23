<?php
//parameter: complain id.
//**status -> pending, scheduled, solved, keep_in_view
//**category -> infrastucture, maintenance, safety and security issues, transportation, others

echo '
{
    "result": {
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
        "description": "KEROSAKAN PERIMETER PAGAR KAWASAN PEJABAT BAHAGIAN TEKNOLOGI MAKLUMAT, IBU PEJABAT JPJ MALAYSIA CYBERJAYA AKIBAT PENEBANGAN POKOK KAWASAN RUMAH KONGSI KONTRAKTOR BERKENAAN",
        "category": {
            "id": 3,
            "name": "maintenance"
        },
        "thumbnail": "https://www.dropbox.com/s/1luo7c2halfzp1v/Fence%20damage.jpg?dl=0",
        "follower_count": 10,
        "parties_involved": [
            {
                "id": 1,
                "name": "TM",
                "full_name": "Telekom Malaysia",
                "type": "internal"
            },
            {
                "id": 35,
                "name": "MPSP",
                "full_name": "Majlis Perbandaran Sepang",
                "type": "external"
            }
        ],
        "created_on": 1439721796,
        "admin": {
            "id": 5,
            "name": "Hafiz"
        },
        "assigned_date": 1439721796,
        "status": "solved",
        "killer": {
            "id": 7,
            "name": "Ahmad"
        },
        "kill_date": 1439721796,
        "comments": [
            {
                "user": {
                    "id": 2,
                    "name": "Alan"
                },
                "comment_date": 1439723268,
                "comment_message": "Sudah lama masih belum fix!"
            },
            {
                "user": {
                    "id": 7,
                    "name": "Ahmad"
                },
                "comment_date": 1439723268,
                "comment_message": "Scheduled for fix on 24/08/2015"
            },
            {
                "user": {
                    "id": 7,
                    "name": "Ahmad"
                },
                "comment_date": 1439723268,
                "comment_message": "Issue solved"
            },
            {
                "user": {
                    "id": 5,
                    "name": "Hafiz"
                },
                "comment_date": 1439723268,
                "comment_message": "Complaint thread close"
            }
        ]
    }
}
';

?>