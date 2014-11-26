import sys
import time
from difflib import SequenceMatcher
text1 = open(sys.argv[1]).read()
text2 = open(sys.argv[2]).read()
m = SequenceMatcher(None, text1, text2)
print(m.ratio())
