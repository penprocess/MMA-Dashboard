from PyPDF2 import PdfReader
from langchain.embeddings.openai import OpenAIEmbeddings
from langchain.text_splitter import CharacterTextSplitter
from langchain.vectorstores import FAISS
from langchain.chains.question_answering import load_qa_chain
from langchain.llms import OpenAI
import sys
import os
import warnings
from dotenv import load_dotenv
import fitz
import logging


logging.getLogger('langchain.text_splitter').setLevel(logging.CRITICAL)


def get_response(user_input, file, start_page=None, end_page=None):
    load_dotenv()
    api_key = os.getenv('OPENAI_API_KEY')

    # Read text from PDF or TXT file
    if file.endswith(".pdf"):
        doc = fitz.open(file)
        raw_text = ""
        if end_page is not None:
            for page_number in range(start_page, end_page + 1):
                page = doc[page_number-1]
                raw_text += page.get_text()
        else:
            for page in doc:
                raw_text += page.get_text()
        doc.close()
    elif file.endswith(".txt"):
        encodings = ['utf-8', 'latin1', 'utf-16']
        for encoding in encodings:
            try:
                with open(file, 'r', encoding=encoding) as f:
                    raw_text = f.read()
                break
            except UnicodeDecodeError:
                pass
    
    # Initialize LangChain components
    text_splitter = CharacterTextSplitter(
        separator=".",
        chunk_size=4000,
        chunk_overlap=0,
        length_function=len,
    )
    texts = text_splitter.split_text(raw_text)
    embeddings = OpenAIEmbeddings(openai_api_key=api_key, model="text-embedding-ada-002")
    docsearch = FAISS.from_texts(texts, embeddings)

    # Load question-answering chain
    chain = load_qa_chain(OpenAI(temperature=0.5, model='gpt-3.5-turbo-instruct', max_tokens=1024), chain_type="refine")

    # Perform question-answering
    result = chain.run(input_documents=docsearch.similarity_search(user_input), question=user_input)
    return result

if __name__ == "__main__":
    if 3 <= len(sys.argv) <= 5:
        user_input = sys.argv[1]
        file = sys.argv[2]
        start_page = int(sys.argv[3]) if sys.argv[3] else None
        end_page = int(sys.argv[4]) if len(sys.argv) == 5 and sys.argv[4] else None
        result = get_response(user_input, file, start_page, end_page)
        print(result)
                                                                                                       
'''if __name__ == "__main__":
    if len(sys.argv) == 3:
        user_input = sys.argv[1]
        file = sys.argv[2]
        result = get_response(user_input, file)
        print(result)

if __name__ == "__main__":
    if len(sys.argv) >= 4:
        user_input = sys.argv[1]
        file = sys.argv[2]
        pages = sys.argv[3]  # Assuming page numbers are given as a single integer or comma-separated integers
        if ',' in pages:
            pages = [int(page) for page in pages.split(',')]  # Convert to list if multiple pages are provided
        else:
            pages = int(pages)  # Convert to integer if only one page is provided
        result = get_response(user_input, file, pages)
        print(result)


result = get_response("explain the waste gas incineration section", "/xampp/htdocs/Project/uploads/9-23.pdf",[13])
print(result)'''


