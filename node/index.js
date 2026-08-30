const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

const app = express();
const server = http.createServer(app);

const io = new Server(server, {
  cors: {
    origin: "*",
  },
});

app.use(cors());
app.use(express.json());

io.on("connection", (socket) => {
  console.log("Admin connected:", socket.id);

  socket.on("disconnect", () => {
    console.log("Admin disconnected:", socket.id);
  });
});

app.post("/product-updated", (req, res) => {
  const { product_id, new_quantity, seller_id } = req.body;

  io.emit("product-updated", {
    product_id,
    new_quantity,
    seller_id,
  });

  return res.json({ status: "ok" });
});

app.post("/order-updated", (req, res) => {
  const { order_id, status, total_price, created_at, seller_id } = req.body;
  
  io.emit("order-updated", {
    order_id,
    status,
    total_price,
    created_at,
    seller_id,
  });

  return res.json({ status: "ok" });
});

const PORT = 3000;

server.listen(PORT, () => {
  console.log(`Socket server listening on http://localhost:${PORT}`);
});
