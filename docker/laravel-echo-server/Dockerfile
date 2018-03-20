
FROM node:7.4

WORKDIR /app
COPY laravel-echo-server.json /app
RUN npm install
EXPOSE 6001

ENTRYPOINT ["/app/node_modules/.bin/laravel-echo-server"]
