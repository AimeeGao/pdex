FROM postgres:15-bookworm

LABEL Kal Marsh <kal.marsh@gov.bc.ca>

ENV PATRONI_VERSION=3.3.0
ENV LC_ALL=en_US.UTF-8 LANG=en_US.UTF-8
ENV PATRONI_HOME=/opt/patroni

ARG PGHOME=/home/postgres

FROM postgres:16-bookworm

LABEL Kal Marsh <kal.marsh@gov.bc.ca>

ENV PATRONI_VERSION=3.3.0
ENV LC_ALL=en_US.UTF-8 LANG=en_US.UTF-8
ENV PATRONI_HOME=/opt/patroni

ARG PGHOME=/home/postgres

RUN export DEBIAN_FRONTEND=noninteractive \
    && set -x \
    # Fix any legacy repository references
    && sed -i 's/deb.debian.org\/debian buster/archive.debian.org\/debian buster/g' /etc/apt/sources.list || true \
    && sed -i 's/security.debian.org\/debian-security buster/archive.debian.org\/debian-security buster/g' /etc/apt/sources.list || true \
    && echo 'APT::Install-Recommends "0";\nAPT::Install-Suggests "0";' > /etc/apt/apt.conf.d/01norecommend \
    && apt-get update -y \
    && apt-get install -y curl jq locales git build-essential libpq-dev python3 python3-dev python3-pip python3-wheel python3-setuptools python3-psycopg2 python3-venv \
    && echo 'Make sure we have a en_US.UTF-8 locale available' \
    && localedef -i en_US -c -f UTF-8 -A /usr/share/locale/locale.alias en_US.UTF-8 \
    # Create virtual environment and install Python packages
    && python3 -m venv /opt/patroni-venv \
    && /opt/patroni-venv/bin/pip install --no-cache-dir psycopg2-binary six psutil pyyaml \
    && /opt/patroni-venv/bin/pip install --no-cache-dir "patroni[kubernetes]==${PATRONI_VERSION}" \
    # Create symlinks so patroni is available globally
    && ln -s /opt/patroni-venv/bin/patroni /usr/local/bin/patroni \
    && ln -s /opt/patroni-venv/bin/patronictl /usr/local/bin/patronictl \
    && PGHOME=/home/postgres \
    && mkdir -p $PGHOME \
    && sed -i "s|/var/lib/postgresql.*|$PGHOME:/bin/bash|" /etc/passwd \
    && echo 'Setting permissions for OpenShift' \
    && chmod 664 /etc/passwd \
    && mkdir -p $PGHOME/pgdata/pgroot \
    && chgrp -R 0 $PGHOME \
    && chown -R postgres $PGHOME \
    && chmod -R 775 $PGHOME \
    && echo 'Cleaning up' \
    && apt-get remove -y git build-essential python3-dev \
    && apt-get autoremove -y \
    && apt-get clean -y \
    && rm -rf /var/lib/apt/lists/* /root/.cache

COPY contrib/root /

# prevent "exec failed: unable to start container process: exec: \"/usr/share/scripts/patroni/health_check.sh\": permission denied"
RUN chmod -R 755 /usr/share/scripts/patroni

VOLUME /home/postgres/pgdata
USER postgres
WORKDIR /home/postgres

EXPOSE 5432 8008

CMD ["/bin/bash", "/usr/bin/entrypoint.sh"]
