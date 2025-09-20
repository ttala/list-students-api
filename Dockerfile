FROM python:3.11-slim

WORKDIR /

COPY . .

RUN apt update -y && apt install build-essential python3-dev \
    libldap2-dev libsasl2-dev libssl-dev -y

RUN pip install -r requirements.txt

EXPOSE 5000

VOLUME /data

CMD ["python3", "./student_age.py"]