<?php

//parameter: complain id.
//**status -> pending, scheduled, solved, keep_in_view
//**category -> infrastucture, maintenance, safety and security issues, transportation, others

echo '
{
    "result": [
        {
            "id": 1,
            "complained_by": {
                "id": 1,
                "name": "Roslan"
            },
            "long": 101.655381,
            "lat": 2.930081,
            "address": "Cyberjaya",
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
            "kill_date": 0
            "resolution_description": "",
            "resolution_image": "",
            "scheduled_date": 1439721796,
            "comments": [
                {
                    "user": {
                        "id": 2,
                        "name": "Alan"
                    },
                    "comment_date": 1439723268,
                    "comment_message": "Sudah lama masih belum fix!"
                }
            ]
        },
        {
            "id": 2,
            "complained_by": {
                "id": 1,
                "name": "Roslan"
            },
            "long": 101.655381,
            "lat": 2.930081,
            "address": "Cyberjaya",
            "title": "Too many littering",
            "description": "Littering in Front of Premises",
            "category": {
                "id": 3,
                "name": "maintenance"
            },
            "thumbnail": "http://www.cbc.ca/polopoly_fs/1.3190259.1439495132!/fileImage/httpImage/image.jpg_gen/derivatives/original_620/trash-and-litter-surround-confederation-building-in-st-john-s.jpg",
            "follower_count": 8,
            "parties_involved": [
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
            "status": "assigned",
            "killer": {
                "id": 7,
                "name": "Ahmad"
            },
            "kill_date": 0
            "resolution_description": "",
            "resolution_image": "",
            "scheduled_date": 1439721796,
            "comments": []
        },
        {
            "id": 3,
            "complained_by": {
                "id": 1,
                "name": "Roslan"
            },
            "long": 101.655381,
            "lat": 2.930081,
            "address": "Cyberjaya",
            "title": "Landscape maintenance",
            "description": "Grass Not Cut in front of Fujitsu ( Malaysia ) Sdn Bhd Building",
            "category": {
                "id": 3,
                "name": "maintenance"
            },
            "thumbnail": "http://www.catholicvote.org/wp-content/uploads/2013/09/long-grass.jpg",
            "follower_count": 6,
            "parties_involved": [
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
            "kill_date": 0
            "resolution_description": "",
            "resolution_image": "",
            "scheduled_date": 1439721796,
            "comments": [
                {
                    "user": {
                        "id": 2,
                        "name": "Alan"
                    },
                    "comment_date": 1439723268,
                    "comment_message": "Sudah lama masih belum fix!"
                }
            ]
        },
        {
            "id": 4,
            "complained_by": {
                "id": 1,
                "name": "Roslan"
            },
            "long": 101.655381,
            "lat": 2.930081,
            "address": "Cyberjaya",
            "title": "No power!",
            "description": "Lodge of Complaint on the Power Supply Shutdown Notice",
            "category": {
                "id": 3,
                "name": "maintenance"
            },
            "thumbnail": "",
            "follower_count": 2,
            "parties_involved": [
                {
                    "id": 48,
                    "name": "TNB",
                    "full_name": "Tenaga Nasional",
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
            "resolution_description": "Base station current level increased",
            "resolution_image": "https://www.dropbox.com/s/1luo7c2halfzp1v/Fence%20damage.jpg?dl=0",
            "scheduled_date": 1439721796,
            "comments": [
                {
                    "user": {
                        "id": 2,
                        "name": "Alan"
                    },
                    "comment_date": 1439723268,
                    "comment_message": "Nice complain!"
                }
            ]
        },
        {
            "id": 5,
            "complained_by": {
                "id": 1,
                "name": "Roslan"
            },
            "long": 101.655381,
            "lat": 2.930081,
            "address": "Cyberjaya",
            "title": "Bus stop no light",
            "description": "Bus stop hsbc no light at night",
            "category": {
                "id": 3,
                "name": "maintenance"
            },
            "thumbnail": "http://www.toxel.com/wp-content/uploads/2008/10/busstopads12.jpg",
            "follower_count": 15,
            "parties_involved": [
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
            "status": "scheduled",
            "killer": {
                "id": 7,
                "name": "Ahmad"
            },
            "kill_date": 0
            "resolution_description": "",
            "resolution_image": "",
            "scheduled_date": 1439721796,
            "comments": [
                {
                    "user": {
                        "id": 2,
                        "name": "Alan"
                    },
                    "comment_date": 1439723268,
                    "comment_message": "Sudah lama masih belum fix!"
                }
            ]
        }
    ]
}
';

?>