// This function handles the incrementing and retrieval of the visitor count
export async function onRequest(context) {
  const { env } = context;
  const counterKey = "total_visitors";

  try {
    // 1. Get current count from KV
    // Fallback to 0 if the key doesn't exist yet
    let count = await env.KV.get(counterKey);
    count = count ? parseInt(count) : 0;

    // 2. Increment the count
    const newCount = count + 1;

    // 3. Save back to KV
    await env.KV.put(counterKey, newCount.toString());

    // 4. Return the new count as JSON
    return new Response(JSON.stringify({ count: newCount }), {
      headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*", // Allows your frontend to call this
      },
    });
  } catch (err) {
    return new Response(JSON.stringify({ error: "Failed to update counter", details: err.message }), {
      status: 500,
      headers: { "Content-Type": "application/json" },
    });
  }
}

