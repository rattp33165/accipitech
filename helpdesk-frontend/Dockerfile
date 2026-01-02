FROM node:20-alpine

WORKDIR /app

# Copy package files และ Install
COPY package*.json ./
RUN npm install

# Copy source code
COPY . .

# Expose Port 5173
EXPOSE 5173

# รัน Dev Server (ต้องมี --host เพื่อให้ Docker มองเห็น)
CMD ["npm", "run", "dev", "--", "--host"]