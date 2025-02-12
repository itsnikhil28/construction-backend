<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #2a5298, #1e3c72);
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
            color: #333333;
        }

        .content p {
            font-size: 16px;
            margin: 10px 0;
        }

        .details {
            margin-top: 20px;
            border-top: 1px solid #eeeeee;
            padding-top: 10px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details table th,
        .details table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #eeeeee;
        }

        .details table th {
            background-color: #f8f9fa;
        }

        .status {
            margin-top: 20px;
            padding: 15px;
            background-color: #cce5ff;
            color: #004085;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #2a5298, #1e3c72);
            color: #ffffff;
            font-size: 14px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Enquiry Received</h1>
        </div>
        <div class="content">
            <p>Dear <strong>{{$data['name']}}</strong>,</p>
            <p>Thank you for reaching out to us. We have received your enquiry and will get back to you as soon as
                possible.</p>
            <p>Here are the details of your enquiry:</p>
            <div class="details">
                <table>
                    <tr>
                        <th>Name</th>
                        <td>{{$data['name']}}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{$data['email']}}</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{$data['number']}}</td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td>{{$data['subject']}}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>{{$data['message']}}</td>
                    </tr>
                </table>
            </div>
            <div class="status">
                Your enquiry has been successfully submitted.
                We will contact you soon with further details.
            </div>
            <p>If you need immediate assistance, please feel free to contact our support team.</p>

            <div style="text-align: center; margin-top: 20px;">
                <p style="font-size: 16px;">Need urgent assistance? Contact us at:</p>
                <p><strong style="color: #1e3c72;">info@example.com</strong></p>
                <p><strong style="color: #1e3c72;">800-800-8000</strong></p>
            </div>
        </div>
        <div class="footer">
            <p style="font-size: 18px; font-weight: bold;">UrbanEdge Constructions</p>
            <p style="font-size: 14px;">B-18X, Rajaji Puram, Civil Lines, Moradabad</p>
        </div>
    </div>
</body>

</html>