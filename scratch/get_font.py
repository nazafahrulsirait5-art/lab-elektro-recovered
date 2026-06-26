import urllib.request
import re

try:
    url = "https://usk.ac.id/"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    html = urllib.request.urlopen(req, timeout=10).read().decode('utf-8')
    
    # find fonts
    fonts = re.findall(r'family=([^&"\']+)', html)
    print("Fonts found:", set(fonts))
except Exception as e:
    print("Error:", e)
