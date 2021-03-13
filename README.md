# Discord for WordPress
Discord Integration for WordPress


## Usage
- Instal and activate plugin
- add shotcode in text.

## Shortcodes
- Join button : [dfwp id="<discord server id>" type="button"]
- Online Players : [dfwp id="<discord server id>" type="oplayers"]
- Player list : [dfwp id="<discord server id>" type="list"]
- Player list whit join button : [dfwp id="<discord server id>" type="joinlist"]

## ToDo

- [x] implement shortcodes
  - [x] Join button
  - [x] Online Players
  - [x] Player list 
  - [x] Player list whit join button
- [ ] basic styling
  - [ ] lists
  - [ ] buttons
- [ ] remove pre installed bugs

## Development

Compile sass command
```Shell
sass --watch dev/dfwp-style.scss:css/dfwp-style.css
```
include php libs
```Shell
composer install
```

## Suggestions or Issues?

- Add a feature request on the GitHub issues page
- Notify me for bugs "Yes Please"
- something missing here ??, let me know.
