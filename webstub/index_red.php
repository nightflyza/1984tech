<?php
$debug = false;

$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$remoteIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$remotePort = isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : '';
$scheme = (!empty($_SERVER['HTTPS']) and $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$actualUrl = $scheme . '://' . $host . $uri;
$accessTime = date('Y-m-d H:i:s');

if ($debug) {
    $logFile = 'log/redirect.log';
    $curDate = date('Y-m-d H:i:s');
    $logData = $remoteIp . ' [' . $curDate . '] ' . $actualUrl . "\n";
    file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
}

function escapeHtml($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Блокування ресурсу!</title>
    <link rel="shortcut icon" href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD/MAAA/5QAAP/WAAD/9AAA//QAAP/YAAD/lAAA/y4AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP8IAAD/lgAA//wAAP//AAD//wAA//8AAP//AAD//wAA//8AAP/8AAD/lgAA/wgAAAAAAAAAAAAAAAAAAP8IAAD/ugAA//8AAP//AAD//wAA//wAAP+GAAD/hAAA//wAAP//AAD//wAA//8AAP+8AAD/CAAAAAAAAAAAAAD/lAAA//8AAP//AAD//wAA//8AAP/IAAAAAAAAAAAAAP/IAAD//wAA//8AAP//AAD//wAA/5QAAAAAAAD/KgAA//wAAP//AAD//wAA//8AAP//AAD/9AAA/0IAAP9CAAD/8gAA//8AAP//AAD//wAA//8AAP/8AAD/LAAA/5AAAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA/5AAAP/QAAD//wAA//8AAP//AAD//wAA//8AAP/SAAD/DgAA/w4AAP/SAAD//wAA//8AAP//AAD//wAA//8AAP/SAAD/8AAA//8AAP//AAD//wAA//8AAP//AAD/0AAAAAAAAAAAAAD/0AAA//8AAP//AAD//wAA//8AAP//AAD/8gAA//AAAP//AAD//wAA//8AAP//AAD//wAA/9AAAAAAAAAAAAAA/9AAAP//AAD//wAA//8AAP//AAD//wAA//AAAP/OAAD//wAA//8AAP//AAD//wAA//8AAP/QAAAAAAAAAAAAAP/QAAD//wAA//8AAP//AAD//wAA//8AAP/OAAD/igAA//8AAP//AAD//wAA//8AAP//AAD/0AAAAAAAAAAAAAD/0AAA//8AAP//AAD//wAA//8AAP//AAD/igAA/yQAAP/6AAD//wAA//8AAP//AAD//wAA/9AAAAAAAAAAAAAA/9AAAP//AAD//wAA//8AAP//AAD/+gAA/yYAAAAAAAD/iAAA//8AAP//AAD//wAA//8AAP/QAAAAAAAAAAAAAP/QAAD//wAA//8AAP//AAD//wAA/4oAAAAAAAAAAAAA/wQAAP+uAAD//wAA//8AAP//AAD/9gAA/9QAAP/UAAD/9gAA//8AAP//AAD//wAA/64AAP8EAAAAAAAAAAAAAAAAAAD/BAAA/4YAAP/4AAD//wAA//8AAP//AAD//wAA//8AAP//AAD/+gAA/4gAAP8EAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD/IAAA/4QAAP/IAAD/6AAA/+gAAP/IAAD/hgAA/yIAAAAAAAAAAAAAAAAAAAAA+B8AAOAHAADAAwAAgYEAAIGBAAAAAAAAAYAAAAGAAAABgAAAAYAAAAGAAACBgQAAgYEAAMADAADgBwAA+B8AAA==">
    <style type="text/css">
        table {
            background-color: #af2324;
            border-collapse: collapse;
            width: 100%;
        }

        td {
            border: 1px solid #af2324;
            border-collapse: collapse;
        }

        body {
            background: #af2324;
            color: #ffffff;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 200;
            line-height: 1.5;
        }

        .container-outer {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            overflow-x: auto;
            padding: 15px;
        }

        .container-inner {
            display: -ms-flexbox;
            display: flex;
            flex-direction: column;
            -ms-flex-direction: column;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            height: 100%;
        }

        .container {
            width: 100%;
        }

        @supports not (-ms-high-contrast: none) {
            .container { margin: auto; }
        }

        h1 {
            margin-bottom: .2em;
            font-weight: bolder;
            line-height: 1.2;
            font-size: 1.4em;
        }

        @media (min-width:576px)  { .container { max-width: 540px; }  body { font-size: 1.0em; } h1 { font-size: 1.4em; } }
        @media (min-width:768px)  { .container { max-width: 720px; }  body { font-size: 1.1em; } h1 { font-size: 1.6em; } }
        @media (min-width:992px)  { .container { max-width: 960px; }  body { font-size: 1.2em; } h1 { font-size: 1.8em; } }
        @media (min-width:1200px) { .container { max-width: 1140px; } body { font-size: 1.3em; } h1 { font-size: 2.0em; } }

        a, a:hover, a:active, a:visited {
            color: #ffffff;
            text-decoration: underline;
        }

        .lead {
            font-size: 1.2em;
        }

        p {
            margin-top: 0;
            margin-bottom: .7em;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            padding-left: 3em;
            padding-bottom: 0.5em;
            padding-top: 0.5em;
            background: left no-repeat;
            background-size: 2em 2em;
        }

        .top-icon {
            height: 5em;
            background: center left no-repeat;
            margin-bottom: 1em;
        }

        button {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid #ffffff;
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            margin: 0 0.5em 0.7em 0;
            padding: 0.4em 0.9em;
        }

        button:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        th {
            text-align: left;
        }

        th#toright {
            text-align: right;
        }

        td#toright {
            text-align: right;
            font-weight: bold;
        }

        td#abuse {
            vertical-align: top;
        }

        .top-icon { background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJJc29sYXRpb25fTW9kZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IgoJIHk9IjBweCIgdmlld0JveD0iMCAwIDc0LjIgNjYuNSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNzQuMiA2Ni41OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ieE1pbllNaWQiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOm5vbmU7c3Ryb2tlOiNGRkZGRkY7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDowLjU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDJ7ZmlsbDojRkZGRkZGO30KCS5zdDN7ZmlsbDojQ0MyOTMxO30KPC9zdHlsZT4KPHBhdGggY2xhc3M9InN0MiIgeD0iMCIgZD0iTTczLjEsNTMuOEw0NC41LDQuMmMtMy4zLTUuNy0xMS40LTUuNy0xNC43LDBMMS4xLDUzLjhDLTIuMSw1OS40LDIsNjYuNSw4LjUsNjYuNWg1Ny4yCglDNzIuMiw2Ni41LDc2LjMsNTkuNCw3My4xLDUzLjh6IE0zNy4xLDU3LjljLTIsMC0zLjYtMS42LTMuNi0zLjZjMC0yLDEuNi0zLjYsMy42LTMuNmMyLDAsMy42LDEuNiwzLjYsMy42CglDNDAuNyw1Ni4zLDM5LjEsNTcuOSwzNy4xLDU3Ljl6IE00MC43LDQyLjJjMCwyLTEuNiwzLjYtMy42LDMuNmMtMiwwLTMuNi0xLjYtMy42LTMuNkwzMSwyNC4xYzAtMy4zLDIuNy02LjEsNi4xLTYuMQoJYzMuMywwLDYuMSwyLjcsNi4xLDYuMUw0MC43LDQyLjJ6Ii8+Cjwvc3ZnPgo='); }
        .first-icon { background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJJc29sYXRpb25fTW9kZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IgoJIHk9IjBweCIgdmlld0JveD0iMCAwIDI1IDI1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyNSAyNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOm5vbmU7c3Ryb2tlOiNGRkZGRkY7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDowLjU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDJ7ZmlsbDojRkZGRkZGO30KCS5zdDN7ZmlsbDojQ0MyOTMxO30KPC9zdHlsZT4KPGc+Cgk8Zz4KCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjIuOCwyMi42bC0wLjIsMC4yYy0wLjYsMC42LTEuNCwwLjYtMiwwbC02LTZjLTAuMS0wLjEtMC4xLTAuMywwLTAuNGwxLjgtMS44YzAuMS0wLjEsMC4zLTAuMSwwLjQsMGw2LDYKCQkJQzIzLjMsMjEuMiwyMy4zLDIyLjEsMjIuOCwyMi42eiIvPgoJCTxwb2x5bGluZSBjbGFzcz0ic3QwIiBwb2ludHM9IjE0LjksMTMuNSAxNi4yLDE0LjggMTQuOCwxNi4yIDEzLjUsMTQuOSAJCSIvPgoJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xNC4yLDRjMi44LDIuOCwyLjgsNy40LDAsMTAuMkMxMS40LDE3LDYuOCwxNyw0LDE0LjJDMS4yLDExLjQsMS4yLDYuOCw0LDRDNi44LDEuMiwxMS40LDEuMiwxNC4yLDR6Ii8+Cgk8L2c+CjwvZz4KPC9zdmc+Cg=='); }
        .second-icon { background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJJc29sYXRpb25fTW9kZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IgoJIHk9IjBweCIgdmlld0JveD0iMCAwIDI1IDI1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyNSAyNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOm5vbmU7c3Ryb2tlOiNGRkZGRkY7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDowLjU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDJ7ZmlsbDojRkZGRkZGO30KCS5zdDN7ZmlsbDojQ0MyOTMxO30KPC9zdHlsZT4KPGc+Cgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjIuNSwxMy42YzAsNS41LTQuNSwxMC0xMCwxMHMtMTAtNC41LTEwLTEwYzAtNS41LDQuNS0xMCwxMC0xMFMyMi41LDguMSwyMi41LDEzLjZ6Ii8+Cgk8cG9seWxpbmUgY2xhc3M9InN0MCIgcG9pbnRzPSIxNC44LDE3IDEyLjUsMTMuNiAxMi41LDYuOCAJIi8+Cgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNOS4xLDQuMVYyLjdjMC0wLjcsMC42LTEuMywxLjMtMS4zaDQuMWMwLjcsMCwxLjMsMC42LDEuMywxLjN2MS41Ii8+CjwvZz4KPC9zdmc+Cg=='); }
        .third-icon { background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJJc29sYXRpb25fTW9kZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IgoJIHk9IjBweCIgdmlld0JveD0iMCAwIDI1IDI1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyNSAyNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOm5vbmU7c3Ryb2tlOiNGRkZGRkY7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDowLjU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDJ7ZmlsbDojRkZGRkZGO30KCS5zdDN7ZmlsbDojQ0MyOTMxO30KPC9zdHlsZT4KPGc+Cgk8Zz4KCQk8cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjIwLjEsMS44IDguNywxLjggNC45LDUuNSA0LjksMjMuMiAyMC4xLDIzLjIgCQkiLz4KCQk8cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjQuOSw1LjUgOC43LDUuNSA4LjcsMS44IAkJIi8+Cgk8L2c+Cgk8Zz4KCQk8bGluZSBjbGFzcz0ic3QwIiB4MT0iNy4yIiB5MT0iOC41IiB4Mj0iMTEuMSIgeTI9IjguNSIvPgoJCTxsaW5lIGNsYXNzPSJzdDAiIHgxPSI3LjIiIHkxPSIxMS4yIiB4Mj0iMTEuMSIgeTI9IjExLjIiLz4KCQk8bGluZSBjbGFzcz0ic3QwIiB4MT0iNy4yIiB5MT0iMTMuOCIgeDI9IjE3LjgiIHkyPSIxMy44Ii8+CgkJPGxpbmUgY2xhc3M9InN0MCIgeDE9IjcuMiIgeTE9IjE2LjUiIHgyPSIxNy44IiB5Mj0iMTYuNSIvPgoJCTxsaW5lIGNsYXNzPSJzdDAiIHgxPSI2LjgiIHkxPSIxOS4yIiB4Mj0iMTcuOCIgeTI9IjE5LjIiLz4KCTwvZz4KCTxnPgoJCTxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik0xNi4zLDYuN0MxNi4zLDYuNywxNi40LDYuNywxNi4zLDYuN0wxNi45LDZjLTAuMS0wLjEtMC4yLTAuMS0wLjMtMC4yTDE2LjMsNi43eiIvPgoJCTxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik0xMi45LDcuN2wwLjksMC4xYzAuMi0wLjcsMC44LTEuMiwxLjUtMS4zbDAuMy0wLjljLTAuMSwwLTAuMSwwLTAuMiwwQzE0LjMsNS42LDEzLjIsNi41LDEyLjksNy43eiIvPgoJCTxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik0xMi45LDguN2MwLjIsMS4xLDEsMS45LDIuMSwyLjFsMC0xYy0wLjYtMC4yLTEtMC42LTEuMS0xLjJMMTIuOSw4Ljd6Ii8+CgkJPHBhdGggY2xhc3M9InN0MSIgZD0iTTE2LDkuOWwwLjEsMC45YzEuMi0wLjIsMi4xLTEuMywyLjEtMi42YzAtMC42LTAuMi0xLjEtMC41LTEuNUwxNyw3LjRjMC4xLDAuMywwLjIsMC41LDAuMiwwLjgKCQkJQzE3LjMsOSwxNi43LDkuNywxNiw5Ljl6Ii8+Cgk8L2c+CjwvZz4KPC9zdmc+Cg=='); }
        .fourth-icon { background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJJc29sYXRpb25fTW9kZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IgoJIHk9IjBweCIgdmlld0JveD0iMCAwIDI1IDI1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyNSAyNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOm5vbmU7c3Ryb2tlOiNGRkZGRkY7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDowLjU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDJ7ZmlsbDojRkZGRkZGO30KCS5zdDN7ZmlsbDojQ0MyOTMxO30KPC9zdHlsZT4KPGc+Cgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTUuMiwxNi4xYy0wLjEsMC42LTAuNSwxLjItMS45LDEuMkg5LjVjLTAuMiwwLTAuMywwLjEtMC40LDAuMkw2LDIwLjd2LTIuOGMwLTAuMy0wLjMtMC42LTAuNi0wLjZIMi41CgkJYy0wLjcsMC0xLjItMC41LTEuMi0xLjJWOC40YzAtMC43LDAuNS0xLjIsMS4yLTEuMmg1LjggTTIzLjcsMTMuOGMwLDAuNS0wLjQsMS0xLDFoLTMuMmMtMC4zLDAtMC42LDAuMy0wLjYsMC42djIuOWwtMy4yLTMuMwoJCWMtMC4xLTAuMS0wLjMtMC4yLTAuNC0wLjJoLTQuN2MtMC41LDAtMS0wLjQtMS0xVjUuM2MwLTAuNSwwLjQtMSwxLTFoMTIuMmMwLjUsMCwxLDAuNCwxLDFWMTMuOHoiLz4KCTxsaW5lIGNsYXNzPSJzdDAiIHgxPSIyMS4zIiB5MT0iNy45IiB4Mj0iMTEuOSIgeTI9IjcuOSIvPgoJPGxpbmUgY2xhc3M9InN0MCIgeDE9IjExLjkiIHkxPSIxMS4yIiB4Mj0iMTUuNiIgeTI9IjExLjIiLz4KPC9nPgo8L3N2Zz4K'); }
        .fifth-icon { background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJJc29sYXRpb25fTW9kZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IgoJIHk9IjBweCIgdmlld0JveD0iMCAwIDI1IDI1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyNSAyNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOm5vbmU7c3Ryb2tlOiNGRkZGRkY7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDowLjU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30KCS5zdDJ7ZmlsbDojRkZGRkZGO30KCS5zdDN7ZmlsbDojQ0MyOTMxO30KPC9zdHlsZT4KPGc+Cgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTYsMTUuN2MtMC45LDAuOS0yLDEuMy0zLjMsMS4zYy0xLjMsMC0yLjQtMC41LTMuMi0xLjNjLTAuOS0wLjktMS40LTItMS40LTMuMmMwLTEuMywwLjUtMi40LDEuNC0zLjIKCQljMC45LTAuOSwyLTEuMywzLjItMS4zYzEuMywwLDIuNCwwLjUsMy4zLDEuM2MwLjksMC45LDEuMywyLDEuMywzLjJDMTcuMywxMy44LDE2LjksMTQuOSwxNiwxNS43eiIvPgoJPHBhdGggY2xhc3M9InN0MCIgZD0iTTE3LjMsNy45VjE3YzAsMi4yLDMsMy40LDQuNCwwLjdjMC44LTEuNiwxLjQtMy4zLDEuNC01LjJjMC0yLjgtMS01LjMtMy03LjNjLTItMi00LjUtMy03LjMtMwoJCWMtMi45LDAtNS4zLDEtNy4zLDNjLTIsMi0zLDQuNS0zLDcuM2MwLDIuOCwxLDUuMywzLDcuM2MyLDIsNC41LDMsNy4zLDNjMS4xLDAsMi4xLTAuMSwzLTAuNCIvPgo8L2c+Cjwvc3ZnPgo='); }
    </style>
</head>
<body>
    <script>
        function show_tech_info() {
            var x = document.getElementById('TechInfo');
            if (x.style.display === 'none') {
                x.style.display = 'block';
            } else {
                x.style.display = 'none';
            }
        }

        function show_recomendation() {
            var x = document.getElementById('Recomendation');
            if (x.style.display === 'none') {
                x.style.display = 'block';
            } else {
                x.style.display = 'none';
            }
        }
    </script>

    <div class="container-outer">
        <div class="container-inner">
            <div class="container">
                <div class="top-icon"></div>
                <h1>Увага! Доступ до цього ресурсу заблоковано</h1>
                <p class="lead">
                Набув чинності Указ президента України №133/2017 "Про рішення Ради національної безпеки і оборони України від 28 квітня 2017 року "Про застосування персональних спеціальних економічних та інших обмежувальних заходів (санкцій)" та Указ президента України №126/2018 від 14 травня 2018 року. 
                Виконуючи вимоги зазначеного <a href="http://www.president.gov.ua/documents/1332017-21850">Указу</a> проводяться технічні заходи з обмеження надання послуг доступу до зазначених веб-ресурсів, відповідно з нашими технічними можливостями. 
                <span id="placeholder"></span>
                </p>

                <button type="button" onclick="show_recomendation()">Рекомендуємо:</button>
                <button type="button" onclick="show_tech_info()">Технічна інформація</button>

                <div id="Recomendation" style="display:none">
                    <ul>
                        <li class="first-icon"><strong>Уважно перевіряйте</strong> адресу та зовнішній вигляд сторінки, на якій ви вводите облікові дані, персональні дані або дані платіжної картки.</li>
                        <li class="second-icon"><strong>Звертайте увагу</strong> на повідомлення, які спонукають Вас діяти негайно. Їх зазвичай використовують зловмисники.</li>
                        <li class="third-icon"><strong>Перевіряйте джерело</strong> інформації, в офіційних джерелах, перш ніж діяти на її основі чи копіювати її.</li>
                        <li class="fourth-icon">Не впевнені чи отримане повідомлення правдиве? <strong>Зв’яжіться</strong> з можливим відправником через інший відомий канал і/або зверніться за підтвердженням інформації до інших джерел.</li>
                        <li class="fifth-icon"><strong>Повідомляйте CERT-UA</strong> про всі підозрілі веб-сайти, а також електронні листи та текстові повідомлення, які можуть бути фішинговими. Форму можна знайти за адресою <a href="https://cert.gov.ua/contact-us">https://cert.gov.ua/contact-us</a>.</li>
                    </ul>
                </div>

                <div id="TechInfo" style="display:none">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <th>Технічна інформація запиту</th>
                                <th style="width:45%">Що робити?</th>
                            </tr>
                            <tr>
                                <td id="toleft">Domain: <?php print(escapeHtml($host)); ?></td>
                                <td rowspan="5" id="abuse">Якщо Ви впевнені, що даний домен не є загрозливим 
                                    та фільтрація відбувається помилково, просимо повідомити про це за адресою: 01220, м. Київ, вул. Банкова, 11.
                                Або ж телефонувати за номером - (044) 255-73-33. </td>
                            </tr>
                            <tr>
                                <td id="toleft">Client IP: <?php print(escapeHtml($remoteIp)); ?></td>
                            </tr>
                            <tr>
                                <td id="toleft">Client Port: <?php print(escapeHtml($remotePort)); ?></td>
                            </tr>
                            <tr>
                                <td id="toleft">URL: <?php print(escapeHtml($actualUrl)); ?></td>
                            </tr>
                            <tr>
                                <td id="toleft">Time of access: <?php print(escapeHtml($accessTime)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
