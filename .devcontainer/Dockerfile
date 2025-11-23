FROM mcr.microsoft.com/devcontainers/base:ubuntu-24.04

# Install vim and PostgreSQL client
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get -y install --no-install-recommends \
        vim \
        postgresql-client \
        wget \
        curl \
        unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Amazon Corretto 21
RUN wget -O- https://apt.corretto.aws/corretto.key | gpg --dearmor -o /usr/share/keyrings/corretto-keyring.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/corretto-keyring.gpg] https://apt.corretto.aws stable main" | tee /etc/apt/sources.list.d/corretto.list \
    && apt-get update \
    && apt-get install -y java-21-amazon-corretto-jdk \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Maven
RUN apt-get update \
    && apt-get install -y maven \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set JAVA_HOME
ENV JAVA_HOME=/usr/lib/jvm/java-21-amazon-corretto
ENV PATH="${JAVA_HOME}/bin:${PATH}"

# Install AWS CLI v2
RUN curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip" \
    && unzip awscliv2.zip \
    && ./aws/install \
    && rm -rf awscliv2.zip aws
