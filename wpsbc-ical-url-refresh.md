# WP Simple Booking Calendar iCal Feed Refresh by calling a URL

This addition to the WP Simple Booking Calendar plugin allows for automated refreshing of iCal feeds via a URL call.

This approach will:

Automatically refresh all iCal feeds for all calendars every hour.
Not require modifying any existing plugin files.
Be secure because it uses a secret key.
Continue to work even if the plugin is updated.

## Installation

1. Upload Or create the file `wpsbc-ical-refresh.php` to your WordPress root directory (where wp-config.php is located).
2. Edit the file and replace `'your_secret_key_here'` with a long, random string of your choice. Do not forget to leave the password surrounded by brackets!

## Usage

### Using a Control Panel (Plesk or cPanel)

1. Access your hosting control panel (Plesk or cPanel).
2. Navigate to the Cron Jobs or Scheduled Tasks section.
3. Create a new cron job with the following settings:
   - Task type: Fetch a URL
   - URL: `https://your-domain.com/wpsbc-ical-refresh.php?key=your_secret_key_here`
   - Schedule: Choose how often you want the refresh to occur (e.g., hourly)

Replace `your-domain.com` with your actual domain and `your_secret_key_here` with the secret key you set in step 2 of the installation.

### Using Command Line

If you have command-line access to your server, you can set up the cron job with the following steps:

1. Open your terminal and connect to your server via SSH.
2. Edit the crontab file by running: 
```
crontab -e
```

3. Add the following line to run the task every hour (If you need different, change 0 * * * * to your desired time interval - https://crontab.guru/)

```
   0 * * * * wget -q -O /dev/null "https://your-domain.com/wpsbc-ical-refresh.php?key=your_secret_key_here" > /dev/null 2>&1
```

Replace `your-domain.com` and `your_secret_key_here` as mentioned above.

4. Save and exit the editor.

## Security Note

This method uses a secret key to prevent unauthorized access to the refresh function. Keep your secret key confidential and avoid sharing the full URL with anyone who shouldn't have access to trigger the refresh.

## Troubleshooting

- Ensure the `wpsbc-ical-refresh.php` file has the correct permissions (usually 644). For Panel users, if you create a new file in Cpanel or Plesk, paste the PHP code, save it with a .php extension, it usually automatically gives it the right permissions
- Check your server's error logs if the refresh isn't working as expected.
- Verify that your WordPress installation and the WP Simple Booking Calendar plugin are up to date.
