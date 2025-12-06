// routes/styles/route.ts
var route = {
  async canvas(context) {
    if (context.search.preview === "stylebook") {
      return null;
    }
    return {
      isPreview: true
    };
  }
};
export {
  route
};
