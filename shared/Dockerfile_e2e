# Use the latest Playwright image
FROM mcr.microsoft.com/playwright:v1.42.0

WORKDIR /app

# Copy package.json and package-lock.json to install dependencies
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the project files
COPY ./ /app/

# Set environment variables (optional)
ENV PLAYWRIGHT_BROWSERS_PATH=/ms-playwright

# Install Playwright Browsers
RUN npx playwright install --with-deps

# Default command to run Playwright tests
CMD ["npx", "playwright", "test"]
