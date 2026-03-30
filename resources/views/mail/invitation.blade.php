<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333333;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7f9;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        /* Button Style */
        .button {
            background-color: #4A90E2;
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        /* Responsive tweaks */
        @media screen and (max-width: 600px) {
            .content { padding: 20px !important; }
        }
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main" width="100%">
            <tr>
                <td style="padding: 40px 0; text-align: center; background-color: #2D3436;">
                    <h1 style="color: #ffffff; margin: 0; font-size: 28px; letter-spacing: 1px;">OUR COMMUNITY: {{ $data["workspace"] }}</h1>
                </td>
            </tr>

            <tr>
                <td style="padding: 40px 40px 20px 40px; text-align: center;">
                    <h2 style="font-size: 24px; color: #2D3436;">Welcome to the family! 🎉</h2>
                    <p style="font-size: 16px; line-height: 1.6; color: #636e72;">
                       
                        Our community is a place to share, learn, and grow together.
                    </p>
                </td>
            </tr>

            <tr>
                <td style="padding: 20px 40px 40px 40px; text-align: center;">
                    <p style="margin-bottom: 25px; font-size: 16px;">Click below to make register to our commubity.</p>
                    <a href="{{$data["url"]}}" class="button">Join the Community</a>
                </td>
            </tr>

            <tr>
                <td style="padding: 30px; text-align: center; background-color: #f1f2f6; color: #b2bec3; font-size: 12px;">
                    <p style="margin: 0;">You received this because you recently invited to join our community.</p>
                    <p style="margin: 10px 0 0 0;">&copy; 2026 Our Community Inc. | 123 Tech Lane, Silicon Valley</p>
                    <p style="margin: 10px 0 0 0;">Manager: Yousef Mohamed</p>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>