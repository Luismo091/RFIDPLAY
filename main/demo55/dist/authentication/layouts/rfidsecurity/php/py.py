import tkinter as tk
from tkinter import scrolledtext

def generate_php_echo():
    html_input = text_area.get("1.0", "end-1c")
    lines = html_input.split('\n')
    php_code = ''

    for line in lines:
        sanitized_line = line.replace("'", "\\'").strip()
        if sanitized_line:
            php_code += "echo '{}';\n".format(sanitized_line)

    php_output.delete("1.0", "end")
    php_output.insert("insert", php_code)

# Create the main window
root = tk.Tk()
root.title("HTML to PHP Echo Converter")

# Create input text area
label = tk.Label(root, text="Enter your HTML fragment:")
label.pack(padx=10, pady=5, anchor="w")

text_area = scrolledtext.ScrolledText(root, wrap=tk.WORD, width=50, height=10)
text_area.pack(padx=10, pady=5)

# Create a button to generate PHP code
generate_button = tk.Button(root, text="Generate PHP Echo", command=generate_php_echo)
generate_button.pack(padx=10, pady=10)

# Create output text area
php_output = scrolledtext.ScrolledText(root, wrap=tk.WORD, width=50, height=10)
php_output.pack(padx=10, pady=5)

# Start the GUI event loop
root.mainloop()
